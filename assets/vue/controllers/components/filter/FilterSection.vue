<script setup>
import FilterItem from "./FilterItem.vue";
import {computed, ref, watch, onMounted, onUnmounted} from "vue";


const emit = defineEmits(['update']);

const props = defineProps(['locationType', 'minPrice', 'minArea'])

const modalIndex = ref(-1);

const locationType = ref(props.locationType);
const minPrice = ref(props.minPrice);
const minArea = ref(props.minArea);

const typeLocation =  {
  alone: "Individuel",
  house_sharing: "Colocation",
  couple: "Couple"
}

function showModel(index) {
  if (modalIndex.value === index) {
    modalIndex.value = -1; // Close the modal if it's already open
    return;
  }
  modalIndex.value = index;
}

// Fermer le modal quand on clique ailleurs
function handleClickOutside(event) {
  // Vérifie si le clic est dans un conteneur de filtre ou un modal
  const isInFilterContainer = event.target.closest('.filter-item-container');
  const isInModal = event.target.closest('.filter-modal');
  
  // Si le clic n'est pas dans un filtre et qu'un modal est ouvert, on le ferme
  if (!isInFilterContainer && !isInModal && modalIndex.value !== -1) {
    modalIndex.value = -1;
  }
}

// Fermer avec la touche Escape
function handleEscape(event) {
  if (event.key === 'Escape' && modalIndex.value !== -1) {
    modalIndex.value = -1;
  }
}

onMounted(() => {
  // Petit délai pour éviter la fermeture immédiate lors du clic d'ouverture
  setTimeout(() => {
    document.addEventListener('click', handleClickOutside, true);
    document.addEventListener('keydown', handleEscape);
  }, 10);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside, true);
  document.removeEventListener('keydown', handleEscape);
});

function onUpdateFilter(index) {

  modalIndex.value = -1;
  switch (index) {
    case 0:
      emit('update', { typeLocation: locationType.value });
      break;
    case 1:
      emit('update', { minPrice: minPrice.value });
      break;
    case 2:
      emit('update', { minArea: minArea.value });
      break;
  }
}

const typeLocationIsUpdated = computed(() => locationType.value !== "");
const minPriceIsUpdated = computed(() => minPrice.value !== null);
const minAreaIsUpdated = computed(() => minArea.value !== null);

function onReset(index) {

  switch (index) {
    case 0:
      typeLocationIsUpdated.value = false;
      locationType.value = "";
      emit('update', { typeLocation: "" });
      break;
    case 1:
      minPriceIsUpdated.value = false;
      minPrice.value = null;
      emit('update', { minPrice: null });
      break;
    case 2:
      minAreaIsUpdated.value = false;
      minArea.value = null;
      emit('update', { minArea: null });
      break;
  }
}


watch(() => [props.minArea, props.minPrice, props.locationType], () => {
  minArea.value = props.minArea;
  minPrice.value = props.minPrice;
  locationType.value = props.locationType;
})

</script>

<template>
  <div class="flex flex-wrap gap-2">
    <div class="filter-item-container" @click.stop>
      <FilterItem 
        @onReset="onReset(0)" 
        :is-updated="typeLocationIsUpdated" 
        :showModal="modalIndex === 0" 
        @onShowModal="() => showModel(0)" 
        name="Type logement" 
        icon="fa-solid fa-house"
      >
      <template #modal>
        <select v-model="locationType" @change="onUpdateFilter(0)" class="w-full bg-white border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-600">
          <option value="">Sélectionner un type</option>
          <option value="couple">Couple</option>
          <option value="house_sharing">Colocation</option>
          <option value="alone">Individuel</option>
        </select>
      </template>
      <template #update-content>
        <span class="mx-1 text-sm sm:text-base">{{ typeLocation[locationType] }}</span>
      </template>
      </FilterItem>
    </div>
    
    <div class="filter-item-container" @click.stop>
      <FilterItem 
        @onReset="onReset(1)" 
        :is-updated="minPriceIsUpdated" 
        :showModal="modalIndex === 1" 
        @onShowModal="() => showModel(1)" 
        name="Prix minimum" 
        icon="fa-solid fa-tag"
      >
      <template #modal>
        <input 
          v-model="minPrice" 
          @change="onUpdateFilter(1)" 
          type="number" 
          placeholder="Prix minimum (€)" 
          class="w-full bg-white border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-600" 
        />
      </template>
      <template #update-content>
        <span class="mx-1 text-sm sm:text-base">{{minPrice}} €</span>
      </template>
      </FilterItem>
    </div>
    
    <div class="filter-item-container" @click.stop>
      <FilterItem 
        @onReset="onReset(2)" 
        :is-updated="minAreaIsUpdated" 
        :showModal="modalIndex === 2" 
        @onShowModal="() => showModel(2)" 
        name="Surface minimum" 
        icon="fa-solid fa-clone"
      >
      <template #modal>
        <input 
          v-model="minArea" 
          @change="onUpdateFilter(2)" 
          type="number" 
          placeholder="Surface minimum (m²)" 
          class="w-full bg-white border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-600" 
        />
      </template>
      <template #update-content>
        <span class="mx-1 text-sm sm:text-base">{{minArea}} m²</span>
      </template>
      </FilterItem>
    </div>
  </div>
</template>

<style scoped>

</style>
