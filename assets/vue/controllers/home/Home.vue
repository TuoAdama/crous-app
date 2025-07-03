<script setup>
import {onMounted, ref} from "vue";
import SearchInput from "./SearchInput.vue";
import HouseItem from "../components/housing/HouseItem.vue";
import NotFoundHouse from "../components/housing/NotFoundHouse.vue";
import SearchService from "../service/SearchService";
import HistoryService from "../service/HistoryService";
import AlertButton from "../components/alert/AlertButton.vue";


const props = defineProps({
configs: Object,
params: Object,
data: Object
});

const isMenuOpen = ref(false);
const showAdvancedFilters = ref(false);
const items = ref([]);
const showAlertBtn = ref(false);
const notFound = ref(false);
const resetInput = ref(false);
const search = ref({
  q: "",
  type: '',
  budgetMax: null,
  surface: null,
});

onMounted(() => {
  search.value = {
    q: props.params.q || '',
    type: props.params.type || '',
    budgetMax: props.params.budgetMax || null,
    surface: props.params.surface || null,
  };
  if (props.data.results) {
    items.value = props.data.results.items || [];
  }
})


  async function onSubmit() {

    const url =  HistoryService.updateHistory(window.location.origin, search.value)
    const apiUrl =  new URL("/api/search/location/", window.location.origin);

    url.searchParams.forEach( (value, key) => {
      apiUrl.searchParams.set(key, value);
    });

    const response = await SearchService.search(apiUrl.toString());

    showAlertBtn.value = true;

    if (!response || !response.results || response.results.length === 0) {
      notFound.value = true;
      items.value = [];
      return;
    }
    notFound.value = false;
    items.value = response.results.items || [];
  }

  function toggleAdvancedFilters() {
    showAdvancedFilters.value = !showAdvancedFilters.value;
  }

  function resetFilters() {
    search.value = {
      type: '',
      budgetMax: null,
      surface: null,
    };
    showCreatedAlertBtn.value = false;
    notFound.value = false;
    // Inverse la valeur pour forcer le reset du SearchInput
    resetInput.value = !resetInput.value;
  }

</script>

<template>
  <nav class="bg-white shadow-md p-4">
    <div class="flex justify-between items-center max-w-7xl mx-auto">
      <div class="text-2xl font-bold text-blue-600">StudentStay</div>
      <div class="md:hidden">
        <button @click="toggleMenu" class="text-gray-600">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>
      </div>
      <div :class="['md:flex md:space-x-4 mt-4 md:mt-0', { 'hidden': !isMenuOpen, 'block': isMenuOpen }]">
        <a href="#" class="block md:inline text-gray-600 hover:text-blue-600 py-2">Accueil</a>
        <a href="#" class="block md:inline text-gray-600 hover:text-blue-600 py-2">Rechercher</a>
        <a href="#" class="block md:inline text-gray-600 hover:text-blue-600 py-2">Favoris</a>
        <a href="#" class="block md:inline text-gray-600 hover:text-blue-600 py-2">Profil</a>
        <button class="block md:inline bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 mt-2 md:mt-0">
          Connexion
        </button>
      </div>
    </div>
  </nav>

  <!-- Section de recherche -->
  <div class="bg-gray-100 min-h-[40vh] flex flex-col justify-center items-center text-center px-4">
    <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 mb-6">Trouvez votre logement étudiant idéal</h1>
    <div class="bg-white p-4 sm:p-6 rounded-lg shadow-md w-full max-w-4xl flex flex-col space-y-4">
      <form class="flex flex-col space-y-4" @submit.prevent="onSubmit">
        <div class="sm:grid sm:grid-cols-2 md:grid-cols-2 gap-4">
          <SearchInput
            @input="(value) => search.q = value"
            :url="props.configs.searchUrl"
            :reset="resetInput"
            :value="params.q || ''"
          />
          <select
              v-model="search.type"
              class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"
          >
            <option value="">Type de logement</option>
            <option value="alone">Individuel</option>
            <option value="couple">Couple</option>
            <option value="house_sharing">Colocation</option>
          </select>
        </div>
        <div v-show="showAdvancedFilters" class="flex-col space-y-4 sm:grid sm:grid-cols-2 md:grid-cols-3 gap-4">
          <!-- Champ Budget max avec styles identiques à Budget min -->
          <input
              v-model.number="search.budgetMax"
              type="number"
              placeholder="Budget max (€)"
              class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"
          />
          <input
              v-model.number="search.surface"
              type="number"
              placeholder="Surface (m²)"
              class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"
          />
        </div>
        <div class="flex flex-col sm:flex-row sm:space-x-4 space-y-4 sm:space-y-0">
          <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200 w-full sm:w-auto">
            Rechercher
          </button>
          <AlertButton v-if="showAlertBtn"/>
          <button
              type="button"
              @click="toggleAdvancedFilters"
              class="bg-gray-200 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-300 transition duration-200 w-full sm:w-auto"
          >
            {{ showAdvancedFilters ? 'Masquer filtre' : 'Filtre avancé' }}
          </button>
        </div>
      </form>
    </div>
  </div>

  <NotFoundHouse v-if="notFound" @reset-filters="resetFilters" />


  <!-- Liste des logements -->
  <div class="p-4 sm:p-8" v-if="items.length > 0">
    <h2 class="text-xl sm:text-2xl font-bold mb-4">Logements disponibles</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      <HouseItem v-for="item in items" :item :key="item.id" :idTool="props.configs.idTool"/>
    </div>
  </div>
</template>

<style scoped>

</style>
