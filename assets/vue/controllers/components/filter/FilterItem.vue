<script setup>
 import FilterModal from "./FilterModal.vue";

 const props = defineProps({
   name: String,
   icon: String,
   showModal: Boolean,
 })

 const emit = defineEmits(['onShowModal']);

 function onCancel() {
   alert("Cancelled: " + props.name);
 }

 function onClick() {
   alert(props.name)
 }

</script>

<template>
  <div @click="emit('onShowModal')" class="relative color-filter p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 mx-2 justify-center items-center p-2">
    <FilterModal v-if="showModal" :title="name">
      <slot></slot>
    </FilterModal>
    <i :class="icon"></i>
    <span class="mr-4 ms-2">{{name}}</span>
<!--    <i class="fa-solid fa-caret-down"></i>-->
    <i class="fa-solid fa-xmark"></i>
  </div>
</template>

<style scoped>

</style>
