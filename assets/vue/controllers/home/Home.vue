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

  <!-- Hero Section avec gradient -->
  <div class="relative bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 min-h-[50vh] flex flex-col justify-center items-center py-12 px-4 overflow-hidden">
    <!-- Animated background elements -->
    <div class="absolute inset-0 overflow-hidden">
      <div class="absolute -top-10 -right-10 w-40 h-40 bg-blue-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
      <div class="absolute -bottom-8 -left-10 w-40 h-40 bg-purple-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
      <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-40 h-40 bg-pink-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
    </div>
    
    <div class="relative w-full max-w-6xl z-10">
      <div class="text-center mb-8">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-800 mb-4 animate-fade-in-down">
          Trouvez votre logement étudiant idéal
        </h1>
        <p class="text-lg sm:text-xl text-gray-600 animate-fade-in-up">
          Des milliers de logements disponibles près de votre campus
        </p>
      </div>
      <div class="bg-white/90 backdrop-blur-sm p-6 sm:p-8 rounded-2xl shadow-xl animate-fade-in">
        <AlertMessage @close="alert.message = ''" v-if="alert.message !== ''" :message="alert.message" :type="alert.type"/>
        <form class="space-y-4">
          <!-- Barre de recherche -->
          <div class="w-full">
            <SearchInput
              @change="updateFilter"
              :url="configs.searchUrl"
              :reset="resetInput"
              :value="search.properties.name || ''"
            />
          </div>
          
          <!-- Filtres et bouton alerte -->
          <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <!-- Section des filtres -->
            <div class="w-full lg:flex-1">
              <FilterSection
                @update="updateFilter"
                :min-area="search.minArea"
                :min-price="search.minPrice"
                :location-type="search.typeLocation"
              />
            </div>
            
            <!-- Bouton créer une alerte -->
            <div class="flex-shrink-0">
              <button 
                type="button" 
                v-if="showAlertBtn" 
                @click="onCreateAlert" 
                class="w-full lg:w-auto flex justify-center items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
              >
                <i class="fa-solid fa-bell"></i>
                <span>Créer une alerte</span>
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <NotFoundHouse v-if="notFound" @reset-filters="resetFilters" />

  <!-- Section Features -->
  <div v-if="items.length === 0 && !notFound" class="py-16 px-4 bg-gray-50">
    <div class="max-w-6xl mx-auto">
      <h2 class="text-2xl sm:text-3xl font-bold text-center text-gray-800 mb-12">Pourquoi choisir notre plateforme ?</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Feature 1 -->
        <div class="group bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
          <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-6 group-hover:bg-blue-200 transition-colors">
            <i class="fa-solid fa-magnifying-glass-location text-2xl text-blue-600"></i>
          </div>
          <h3 class="text-xl font-semibold mb-3 text-gray-800">Recherche avancée</h3>
          <p class="text-gray-600">Trouvez facilement le logement idéal grâce à nos filtres détaillés et notre système de recherche intelligent.</p>
        </div>
        
        <!-- Feature 2 -->
        <div class="group bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
          <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-6 group-hover:bg-green-200 transition-colors">
            <i class="fa-solid fa-shield-halved text-2xl text-green-600"></i>
          </div>
          <h3 class="text-xl font-semibold mb-3 text-gray-800">100% Sécurisé</h3>
          <p class="text-gray-600">Tous les logements sont vérifiés et conformes aux normes étudiantes. Votre sécurité est notre priorité.</p>
        </div>
        
        <!-- Feature 3 -->
        <div class="group bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
          <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mb-6 group-hover:bg-purple-200 transition-colors">
            <i class="fa-solid fa-bell text-2xl text-purple-600"></i>
          </div>
          <h3 class="text-xl font-semibold mb-3 text-gray-800">Alertes personnalisées</h3>
          <p class="text-gray-600">Créez des alertes pour être notifié dès qu'un logement correspondant à vos critères est disponible.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Section Statistiques -->
  <div v-if="items.length === 0 && !notFound" class="py-16 px-4 bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
    <div class="max-w-6xl mx-auto">
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 text-center">
        <div class="space-y-2">
          <div class="text-4xl sm:text-5xl font-bold animate-count">5000+</div>
          <div class="text-lg opacity-90">Logements disponibles</div>
        </div>
        <div class="space-y-2">
          <div class="text-4xl sm:text-5xl font-bold animate-count">50+</div>
          <div class="text-lg opacity-90">Villes universitaires</div>
        </div>
        <div class="space-y-2">
          <div class="text-4xl sm:text-5xl font-bold animate-count">98%</div>
          <div class="text-lg opacity-90">Étudiants satisfaits</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Call to Action Section -->
  <div v-if="items.length === 0 && !notFound" class="py-16 px-4 bg-white">
    <div class="max-w-4xl mx-auto text-center">
      <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-6">Prêt à trouver votre futur logement ?</h2>
      <p class="text-lg text-gray-600 mb-8">Commencez votre recherche dès maintenant et trouvez le logement parfait pour vos études</p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <button class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
          <i class="fa-solid fa-search mr-2"></i>
          Commencer la recherche
        </button>
        <button class="px-8 py-3 bg-white border-2 border-gray-300 hover:border-gray-400 text-gray-700 rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
          <i class="fa-solid fa-info-circle mr-2"></i>
          En savoir plus
        </button>
      </div>
    </div>
  </div>

  <!-- Liste des logements -->
  <div class="p-4 sm:p-8 bg-gray-50" v-if="items.length > 0">
    <div class="max-w-7xl mx-auto">
      <div class="flex flex-col sm:flex-row justify-between items-center mb-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-4 sm:mb-0">Logements disponibles</h2>
        <div class="flex items-center gap-2 text-gray-600">
          <i class="fa-solid fa-house-chimney"></i>
          <span class="font-medium">{{ items.length }} résultats trouvés</span>
        </div>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <HouseItem v-for="(item, index) in items" :item :key="item.id" :idTool="configs.idTool" :style="{ animationDelay: `${index * 0.1}s` }" class="animate-fade-in-up"/>
      </div>
    </div>
  </div>
</template>

<style scoped>
@keyframes blob {
  0% {
    transform: translate(0px, 0px) scale(1);
  }
  33% {
    transform: translate(30px, -50px) scale(1.1);
  }
  66% {
    transform: translate(-20px, 20px) scale(0.9);
  }
  100% {
    transform: translate(0px, 0px) scale(1);
  }
}

.animate-blob {
  animation: blob 7s infinite;
}

.animation-delay-2000 {
  animation-delay: 2s;
}

.animation-delay-4000 {
  animation-delay: 4s;
}

@keyframes fade-in-down {
  0% {
    opacity: 0;
    transform: translateY(-20px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fade-in-up {
  0% {
    opacity: 0;
    transform: translateY(20px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fade-in {
  0% {
    opacity: 0;
  }
  100% {
    opacity: 1;
  }
}

.animate-fade-in-down {
  animation: fade-in-down 0.8s ease-out;
}

.animate-fade-in-up {
  animation: fade-in-up 0.8s ease-out;
  animation-fill-mode: both;
}

.animate-fade-in {
  animation: fade-in 1s ease-out;
}

@keyframes count {
  0% {
    opacity: 0;
    transform: scale(0.5);
  }
  100% {
    opacity: 1;
    transform: scale(1);
  }
}

.animate-count {
  animation: count 1s ease-out;
}
</style>
