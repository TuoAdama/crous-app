<script setup>
import FilterItem from "./FilterItem.vue";
import {ref} from "vue";


const emit = defineEmits(['update']);

const modalIndex = ref(-1);
const typeLocationIsUpdated = ref(false);
const minPriceIsUpdated = ref(false);
const minAreaIsUpdated = ref(false);

const locationType = ref("");
const minPrice = ref(null);
const minArea = ref(null);

function showModel(index) {
  if (modalIndex.value === index) {
    modalIndex.value = -1; // Close the modal if it's already open
    return;
  }
  modalIndex.value = index;
}

function onUpdateFilter(index) {

  modalIndex.value = -1;

  switch (index) {
    case 0:
      typeLocationIsUpdated.value = true;
      emit('update', { typeLocation: locationType.value });
      break;
    case 1:
      minPriceIsUpdated.value = true;
      emit('update', { minPrice: minPrice.value });
      break;
    case 2:
      minAreaIsUpdated.value = true;
      emit('update', { minArea: minArea.value });
      break;
  }
}

function onReset(index) {

  switch (index) {
    case 0:
      typeLocationIsUpdated.value = false;
      locationType.value = "";
      break;
    case 1:
      minPriceIsUpdated.value = false;
      minPrice.value = null;
      break;
    case 2:
      minAreaIsUpdated.value = false;
      minArea.value = null;
      break;
  }
}

</script>

<template>
  <div class="flex justify-between items-center">
    <div class="flex">
      <FilterItem @onReset="onReset(0)" :is-updated="typeLocationIsUpdated" :showModal="modalIndex === 0" @onShowModal="() => showModel(0)" name="Type logement" icon="fa-solid fa-house">
        <template #modal>
          <select v-model="locationType" @change="onUpdateFilter(0)" class="w-full bg-white border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-600">
            <option value="couple">Couple</option>
            <option value="colocation">Colocation</option>
            <option value="individuel">Individuel</option>
          </select>
        </template>
        <template #update-content>
          <span class="mx-2">Type: {{locationType}}</span>
        </template>
      </FilterItem>
      <FilterItem @onReset="onReset(1)" :is-updated="minPriceIsUpdated" :showModal="modalIndex === 1" @onShowModal="() => showModel(1)" name="Prix minimum" icon="fa-solid fa-tag">
        <template #modal>
          <input v-model="minPrice" @change="onUpdateFilter(1)" type="number" placeholder="Prix minimum (€)" class="w-full bg-white border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-600" />
        </template>
        <template #update-content>
          <span class="mx-2">Min: {{minPrice}} €</span>
        </template>
      </FilterItem>
      <FilterItem @onReset="onReset(2)" :is-updated="minAreaIsUpdated" :showModal="modalIndex === 2" @onShowModal="() => showModel(2)" name="Surface minimum" icon="fa-solid fa-clone">
        <template #modal>
          <input v-model="minArea" @change="onUpdateFilter(2)" type="number" placeholder="Surface minimum (m²)" class="w-full bg-white border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-600" />
        </template>
        <template #update-content>
          <span class="mx-2">Surface: {{minArea}} m²</span>
        </template>
      </FilterItem>
    </div>
    <div>
      <button style="background-color: #b91c1c" class="flex rounded-md items-center gap-2 px-4 py-2 text-white">
        <i class="fa-solid fa-bell"></i>
        Créer une alerte
      </button>
    </div>
  </div>
</template>

<style scoped>

</style>
