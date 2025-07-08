<script setup>
import FilterItem from "./FilterItem.vue";
import {ref} from "vue";

const modalIndex = ref(-1);

function showModel(index) {
  if (modalIndex.value === index) {
    modalIndex.value = -1; // Close the modal if it's already open
    return;
  }
  modalIndex.value = index;
}

</script>

<template>
  <div class="flex justify-between items-center">
    <div class="flex">
      <FilterItem :showModal="modalIndex === 0" @onShowModal="() => showModel(0)" name="Type logement" icon="fa-solid fa-house">
        <select class="w-full bg-white border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-600">
          <option value="couple">Couple</option>
          <option value="colocation">Colocation</option>
          <option value="individuel">Individuel</option>
        </select>
      </FilterItem>
      <FilterItem :showModal="modalIndex === 1" @onShowModal="() => showModel(1)" name="Prix minimum" icon="fa-solid fa-tag">
        <input type="number" placeholder="Prix minimum (€)" class="w-full bg-white border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-600" />
      </FilterItem>
      <FilterItem :showModal="modalIndex === 2" @onShowModal="() => showModel(2)" name="Surface minimum" icon="fa-solid fa-clone">
        <input type="number" placeholder="Surface minimum (m²)" class="w-full bg-white border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-600" />
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
