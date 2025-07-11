<script setup>
import {onMounted, ref} from "vue";
import SearchInput from "./SearchInput.vue";
import HouseItem from "../components/housing/HouseItem.vue";
import NotFoundHouse from "../components/housing/NotFoundHouse.vue";
import SearchService from "../service/SearchService";
import HistoryService from "../service/HistoryService";
import AlertButton from "../components/alert/AlertButton.vue";
import Navbar from "./Navbar.vue";
import FilterSection from "../components/filter/FilterSection.vue";


const props = defineProps({
configs: Object,
params: Object,
data: Object,
user: Object,
});

const showAdvancedFilters = ref(false);
const items = ref([]);
const showAlertBtn = ref(false);
const notFound = ref(false);
const resetInput = ref(false);
const search = ref({
  typeLocation: '',
  minPrice: 0,
  minArea: 0,
  properties: {},
});

onMounted(() => {

  search.value = {
    typeLocation: props.params.type || '',
    minPrice: props.params.budgetMax || null,
    minArea: props.params.surface || null,
    properties: {
      extent: props.params.extent ??  [],
    },
  };
  if (props.data.results) {
    items.value = props.data.results.items || [];
  }
})

  function toggleAdvancedFilters() {
    showAdvancedFilters.value = !showAdvancedFilters.value;
  }

  function resetFilters() {
    search.value = {
      typeLocation: '',
      minPrice: null,
      minArea: null,
    };
    showCreatedAlertBtn.value = false;
    notFound.value = false;
    // Inverse la valeur pour forcer le reset du SearchInput
    resetInput.value = !resetInput.value;
  }

  function updateFilter(value) {
    search.value = {
      ...search.value,
      ...value,
    };

    if (Object.keys(search.value.properties).length === 0) {
      return;
    }
    makeRequest();
  }


  async function makeRequest() {
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

  async function onCreateAlert(){

    const {typeLocation, minPrice, minArea, properties} = search.value;

    const response = await fetch('/api/search/create-alert', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        type: typeLocation,
        min_area: minArea,
        min_price: minPrice,
        extent: properties.extent,
        name: properties.name,
      })
    })

    const data = await response.json()

    console.log({data})
  }


</script>

<template>

  <Navbar :is-auth="props.user !== null"/>

  <!-- Section de recherche -->
  <div class="bg-gray-100 min-h-[40vh] flex flex-col justify-center items-center text-center px-4">
    <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 mb-6">Trouvez votre logement étudiant idéal</h1>
    <div class="bg-white p-4 sm:p-6 rounded-lg shadow-md w-full max-w-4xl flex flex-col space-y-4">
      <form class="flex flex-col space-y-4" @submit.prevent="onSubmit">
        <div class="">
          <SearchInput
            @change="updateFilter"
            :url="props.configs.searchUrl"
            :reset="resetInput"
            :value="params.name || ''"
          />
        </div>
        <div class="flex justify-between items-center">
          <FilterSection @update="updateFilter"/>
          <div>
            <button @click="onCreateAlert" :disabled="search.properties.extent === undefined" style="background-color: #b91c1c" class="flex rounded-md items-center gap-2 px-4 py-2 text-white">
              <i class="fa-solid fa-bell"></i>
              Créer une alertes
            </button>
          </div>
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
