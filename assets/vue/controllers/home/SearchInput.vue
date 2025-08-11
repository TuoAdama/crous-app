<template>
  <div class="relative w-full">
    <div class="relative">
      <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
        <i class="fas fa-search text-gray-400 text-lg"></i>
      </div>
      <input
          type="text"
          v-model="query"
          @input="onInput"
          @keydown.down.prevent="onArrowDown"
          @keydown.up.prevent="onArrowUp"
          @keydown.enter.prevent="onEnter"
          @focus="showSuggestions = true"
          @blur="hideSuggestions"
          placeholder="Rechercher une ville (ex. Paris, Lyon, Marseille...)"
          class="w-full pl-12 pr-4 py-4 text-base border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-300 bg-white shadow-sm hover:shadow-md"
      />
      <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
        <div v-if="query.length > 0" @click="clearQuery" class="cursor-pointer p-1 rounded-full hover:bg-gray-100 transition-colors duration-200">
          <i class="fas fa-times text-gray-400 hover:text-gray-600"></i>
        </div>
      </div>
    </div>

    <!-- Suggestions -->
    <div
        v-if="showSuggestions && filteredSuggestions.length"
        class="absolute z-50 w-full mt-2 bg-white border border-gray-200 rounded-xl shadow-xl max-h-80 overflow-hidden"
    >
      <div class="p-2">
        <div class="text-xs font-medium text-gray-500 uppercase tracking-wide px-3 py-2">
          Villes trouvées
        </div>
        <ul class="space-y-1">
          <li
              v-for="(suggestion, index) in filteredSuggestions"
              :key="index"
              @mousedown.prevent="selectSuggestion(suggestion)"
              :class="[
                'px-3 py-3 cursor-pointer rounded-lg transition-all duration-200 flex items-center',
                index === highlightedIndex 
                  ? 'bg-gradient-to-r from-blue-50 to-purple-50 border border-blue-200' 
                  : 'hover:bg-gray-50'
              ]"
          >
            <div class="flex items-center w-full">
              <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                <i class="fas fa-map-marker-alt text-white text-sm"></i>
              </div>
              <div class="flex-1 min-w-0">
                <div class="font-medium text-gray-800 truncate">
                  {{ suggestion.text }}
                </div>
                <div class="text-sm text-gray-500 flex items-center">
                  <i class="fas fa-flag mr-1"></i>
                  <span>{{ suggestion.properties.country }}</span>
                </div>
              </div>
              <div v-if="index === highlightedIndex" class="ml-2">
                <i class="fas fa-arrow-right text-blue-500"></i>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>

    <!-- Loading indicator -->
    <div v-if="isLoading" class="absolute inset-y-0 right-0 pr-4 flex items-center">
      <div class="animate-spin rounded-full h-5 w-5 border-2 border-blue-500 border-t-transparent"></div>
    </div>
  </div>
</template>

<script setup>
import {ref, watch} from 'vue';

const props = defineProps({
  url: String,
  onSubmit: Function,
  reset: Boolean,
  value: {
    type: String,
    default: '',
  },
})

const emit = defineEmits(['change']);

const parameters = {
  country: "France",
  limit: 18,
  lang: "fr",
  minimumInputLength: 3,
}

const query = ref(props.value);
const showSuggestions = ref(false);
const highlightedIndex = ref(-1);
const isLoading = ref(false);

const filteredSuggestions = ref([])

function onInput() {
  if (query.value.length < parameters.minimumInputLength) {
    showSuggestions.value = false;
    filteredSuggestions.value = [];
    return;
  }
  onSearch();
  showSuggestions.value = true;
  highlightedIndex.value = -1;
}

function onArrowDown() {
  if (highlightedIndex.value < filteredSuggestions.value.length - 1) {
    highlightedIndex.value++;
  } else {
    highlightedIndex.value = 0; // Loop to top
  }
}

function onSearch(){
  isLoading.value = true;
  const url = new URL(props.url)
  url.searchParams.set('q', query.value);

  fetch(url, {
    method: 'GET',
  }).then(response => {
    if (response.ok) {
      return response.json();
    } else {
      throw new Error('Network response was not ok');
    }
  }).then(data => {
    filteredSuggestions.value = formatData(data);
    isLoading.value = false;
  }).catch(error => {
    console.error('There has been a problem with your fetch operation:', error);
    isLoading.value = false;
  });
}

function onArrowUp() {
  if (highlightedIndex.value > 0) {
    highlightedIndex.value--;
  } else {
    highlightedIndex.value = filteredSuggestions.value.length - 1; // Loop to bottom
  }
}

function onEnter() {
  if (
      highlightedIndex.value >= 0 &&
      highlightedIndex.value < filteredSuggestions.value.length
  ) {
    selectSuggestion(filteredSuggestions.value[highlightedIndex.value]);
  }
}

function selectSuggestion(value) {
  query.value = value.properties.name;
  showSuggestions.value = false;
  highlightedIndex.value = -1;
  emit('change', {properties: value.properties});
}

function clearQuery() {
  query.value = '';
  filteredSuggestions.value = [];
  highlightedIndex.value = -1;
  showSuggestions.value = false;
  emit('change', {properties: {name: ''}});
}

function hideSuggestions() {
  setTimeout(() => {
    showSuggestions.value = false;
  }, 150);
}

function formatData(data) {
  let cities = data.features;
  if (cities.length === 0) {
    return [];
  }
  cities = cities.filter(function (city) {
    return city.properties.country === parameters.country
  });
  if (cities.length === 0) {
    return [];
  }
  return cities.map(function (result) {
    const postcode = result.properties.postcode;
    const nameWithPostalCode = result.properties.name + (
        postcode ? ` (${result.properties.postcode})` : ''
    );
    return {
      text: nameWithPostalCode,
      properties: result.properties,
    }
  });
}

// Ajout du watcher pour reset le champ de recherche (reset est maintenant un booléen)
watch(() => [props.reset, props.value], ([newReset, newValue], [prevReset, prevValue]) => {
  if (newReset !== prevReset){
    query.value = '';
    filteredSuggestions.value = [];
    highlightedIndex.value = -1;
    showSuggestions.value = false;
  }
  if (newValue !== prevValue){
    query.value = newValue
  }
});
</script>

<style scoped>
/* Animations personnalisées */
@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-slide-down {
  animation: slideDown 0.2s ease-out;
}
</style>
