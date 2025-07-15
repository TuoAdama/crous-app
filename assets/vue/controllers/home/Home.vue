<script setup>
import {computed, onMounted, ref} from "vue";
import SearchInput from "./SearchInput.vue";
import HouseItem from "../components/housing/HouseItem.vue";
import NotFoundHouse from "../components/housing/NotFoundHouse.vue";
import SearchService from "../service/SearchService";
import HistoryService from "../service/HistoryService";
import Navbar from "./Navbar.vue";
import FilterSection from "../components/filter/FilterSection.vue";
import AlertMessage from "../components/alert/AlertMessage.vue";
import {AlertType} from "../enum/AlertType";


const STORAGE_KEY = "search"


const {configs, params, user, data} = defineProps({
configs: Object,
params: Object,
data: Object,
user: Object,
});

const items = ref(data.results ? data.results.items : []);
const notFound = ref(false);
const resetInput = ref(false);
const alert = ref({
  message: "",
  type: ""
});

const search = ref({
  typeLocation: params.type ?? '',
  minPrice: params.minPrice ?? null,
  minArea: params.minArea ?? null,
  properties: {
    extent: params.extent ?? [],
    name: params.name ?? "",
  },
});

const showAlertBtn =  computed(() => {
  return search.value.properties.extent.length > 0;
});

onMounted(() => {
  let oldSearch = localStorage.getItem(STORAGE_KEY)
  if (oldSearch !== null){
    search.value = JSON.parse(oldSearch)
    localStorage.removeItem(STORAGE_KEY)
  }
})

  function resetFilters() {
    search.value = {
      typeLocation: '',
      minPrice: null,
      minArea: null,
      properties: {
        extent: [],
        name: "",
      }
    };
    notFound.value = false;
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

    let extent = properties.extent;
    if (extent instanceof Array){
      extent = extent.join(',');
    }

    const payload = {
      type: typeLocation,
      min_area: minArea,
      min_price: minPrice,
      extent,
      name: properties.name,
    };

    const response = await fetch('/api/search/create-alert', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(payload)
    })

    if (response.status === 201){
      alert.value.message = AlertType.ALERT_SUCCESS_MESSAGE;
      alert.value.type = AlertType.SUCCESS
    }else if (response.status === 401){
      localStorage.setItem(STORAGE_KEY, JSON.stringify(search.value))
      window.location.href = "/login?redirect=app_home";
    } else {
      alert.value.message = AlertType.ALERT_ERROR_MESSAGE;
      alert.value.type = AlertType.DANGER
    }
  }


</script>

<template>

  <Navbar :is-auth="user !== null"/>

  <!-- Section de recherche -->
  <div class="bg-gray-100 min-h-[40vh] flex flex-col justify-center items-center text-center px-4">
    <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 mb-6">Trouvez votre logement étudiant idéal</h1>
    <div class="bg-white p-4 sm:p-6 rounded-lg shadow-md w-full max-w-4xl flex flex-col space-y-4">
      <AlertMessage @close="alert.message = ''" v-if="alert.message !== ''" :message="alert.message" :type="alert.type"/>
      <form class="flex flex-col space-y-4">
        <div class="">
          <SearchInput
            @change="updateFilter"
            :url="configs.searchUrl"
            :reset="resetInput"
            :value="search.properties.name || ''"
          />
        </div>
        <div class="flex justify-between items-center">
          <FilterSection
              @update="updateFilter"
              :min-area="search.minArea"
              :min-price="search.minPrice"
              :location-type="search.typeLocation"
          />
          <div>
            <button type="button" v-if="showAlertBtn" @click="onCreateAlert" style="background-color: #b91c1c" class="flex rounded-md items-center gap-2 px-4 py-2 text-white">
              <i class="fa-solid fa-bell"></i>
              Créer une alerte
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
      <HouseItem v-for="item in items" :item :key="item.id" :idTool="configs.idTool"/>
    </div>
  </div>
</template>

<style scoped>

</style>
