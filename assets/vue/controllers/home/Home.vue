<script setup>
  import {ref} from "vue";
  import SearchInput from "./SearchInput.vue";
  import HouseItem from "../components/housing/HouseItem.vue";
  const isMenuOpen = ref(false);
  const showAdvancedFilters = ref(false);

  const props = defineProps({
    configs: Object,
  })

  const search = ref({
    city: '',
    type: '',
    budgetMax: null,
    surface: null,
  });

  const items = ref([]);
  const results = ref({});
  const showCreatedAlertBtn = ref(false);
  function toggleMenu() {
    isMenuOpen.value = !isMenuOpen.value;
  }

  // onChange Budget Max
  function onChangeBugdetMax() {
    apply();
  }

  function onChangArea() {
    apply();
  }

  function onchangeOccupationMode() {
    apply();
  }

  function apply() {
    if (!results.value || Object.keys(results.value).length === 0) {
      console.log(results.value)
      return;
    }
    showCreatedAlertBtn.value = true;

    const requestBody = {
      location: results.value,
      area: {
        min: search.value.surface || 0,
      },
      price: {
        max: search.value.budgetMax || 300,
      },
      occupationModes: search.value.type !== "" ? [search.value.type] : [],
    }

    console.log({requestBody, results: results.value})

    fetch("/api/search/results", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify(requestBody),
    }).then(response => {
      if (response.ok) {
        return response.json();
      } else {
        throw new Error('Network response was not ok');
      }
    }).then(response => {
      let results = response.results;

      console.log({results})
      if (results && results.items){
        items.value = results.items;
      }
    }).catch(error => {
      console.error('There was a problem with the fetch operation:', error);
    });
  }

  function toggleAdvancedFilters() {
    showAdvancedFilters.value = !showAdvancedFilters.value;
  }
  function onSubmit(value) {
    results.value = value.results;
    apply();
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
  <div class="bg-gray-100 min-h-[50vh] flex flex-col justify-center items-center text-center px-4">
    <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 mb-6">Trouvez votre logement étudiant idéal</h1>
    <div class="bg-white p-4 sm:p-6 rounded-lg shadow-md w-full max-w-4xl flex flex-col space-y-4">
      <form class="flex flex-col space-y-4">
        <div class="sm:grid sm:grid-cols-2 md:grid-cols-2 gap-4">
          <SearchInput :url="props.configs.searchUrl" :onSubmit="onSubmit"/>
          <select
              v-model="search.type"
              @change="onchangeOccupationMode"
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
              @change="onChangeBugdetMax"
              v-model.number="search.budgetMax"
              type="number"
              placeholder="Budget max (€)"
              class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"
          />
          <input
              v-model.number="search.surface"
              @change="onChangArea"
              type="number"
              placeholder="Surface (m²)"
              class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"
          />
        </div>
        <div class="flex flex-col sm:flex-row sm:space-x-4 space-y-4 sm:space-y-0">
          <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200 w-full sm:w-auto">
            Rechercher
          </button>
          <button v-if="showCreatedAlertBtn" style="background-color: #b91c1c" type="submit" class="text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200 w-full sm:w-auto">
            Créer une alerte
          </button>
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

  <!-- Liste des logements -->
  <div class="p-4 sm:p-8">
    <h2 class="text-xl sm:text-2xl font-bold mb-4">Logements disponibles</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      <HouseItem v-for="item in items" :item :key="item.id" :idTool="props.configs.idTool"/>
    </div>
  </div>
</template>

<style scoped>

</style>
