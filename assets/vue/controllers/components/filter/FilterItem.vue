<script setup>
 import FilterModal from "./FilterModal.vue";

 const props = defineProps({
   name: String,
   icon: String,
   showModal: Boolean,
   isUpdated: Boolean,
 })

 const emit = defineEmits(['onShowModal', 'onReset']);

</script>

<template>
  <div @click="emit('onShowModal')"
       :class="['relative p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 mx-2 justify-center items-center p-2', isUpdated ? 'color-filter' : 'bg-white']"
  >
    <FilterModal v-if="showModal" :title="name" @blur="emit('onShowModal')">
      <slot name="modal"></slot>
    </FilterModal>
    <i :class="icon"></i>
    <span v-if="!isUpdated" class="mr-4 ms-2">{{name}}</span>
    <slot v-if="isUpdated" name="update-content"></slot>
    <i v-if="!isUpdated" class="fa-solid fa-caret-down"></i>
    <i @click.stop="emit('onReset')" v-if="isUpdated" class="fa-solid fa-xmark hover:bg-red-100"></i>
  </div>
</template>

<style scoped>

</style>
