<template>
  <div class="relative w-full">
    <input
        type="text"
        v-model="query"
        @input="onInput"
        @keydown.down.prevent="onArrowDown"
        @keydown.up.prevent="onArrowUp"
        @keydown.enter.prevent="onEnter"
        @focus="showSuggestions = true"
        @blur="hideSuggestions"
        placeholder="Ville (ex. Paris, Lyon)"
        class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"
    />

    <ul
        v-if="showSuggestions && filteredSuggestions.length"
        class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg max-h-60 overflow-y-auto"
    >
      <li
          v-for="(suggestion, index) in filteredSuggestions"
          :key="index"
          @mousedown.prevent="selectSuggestion(suggestion)"
          :class="[
          'px-4 py-2 cursor-pointer',
          index === highlightedIndex ? 'bg-blue-100' : 'hover:bg-gray-100',
        ]"
      >
        {{ suggestion.text }}
      </li>
    </ul>
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

const filteredSuggestions = ref([])

function onInput() {
  if (query.value.length < parameters.minimumInputLength) {
    showSuggestions.value = false;
    return;
  }
  onSearch();
  showSuggestions.value = true;
  highlightedIndex.value = -1;
}

function onArrowDown() {
  if (highlightedIndex.value < filteredSuggestions.value.length - 1) {
    highlightedIndex.value++;
  }
}

function onSearch(){
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
  }).catch(error => {
    console.error('There has been a problem with your fetch operation:', error);
  });
}

function onArrowUp() {
  if (highlightedIndex.value > 0) {
    highlightedIndex.value--;
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

function hideSuggestions() {
  setTimeout(() => {
    showSuggestions.value = false;
  }, 100);
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
