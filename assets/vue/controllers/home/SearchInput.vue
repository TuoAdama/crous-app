<template>
  <div class="relative w-full max-w-md">
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
        {{ suggestion }}
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const query = ref('');
const showSuggestions = ref(false);
const highlightedIndex = ref(-1);

const suggestions = [
  'Paris',
  'Lyon',
  'Marseille',
  'Toulouse',
  'Nice',
  'Nantes',
  'Strasbourg',
  'Montpellier',
  'Bordeaux',
  'Lille',
];

const filteredSuggestions = computed(() => {
  const q = query.value.toLowerCase();
  return q ? suggestions.filter((item) => item.toLowerCase().includes(q)) : [];
});

function onInput() {
  showSuggestions.value = true;
  highlightedIndex.value = -1;
}

function onArrowDown() {
  if (highlightedIndex.value < filteredSuggestions.value.length - 1) {
    highlightedIndex.value++;
  }
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
  query.value = value;
  showSuggestions.value = false;
  highlightedIndex.value = -1;
}

function hideSuggestions() {
  setTimeout(() => {
    showSuggestions.value = false;
  }, 100);
}
</script>
