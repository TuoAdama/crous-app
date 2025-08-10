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
  <div 
    @click="emit('onShowModal')"
    :class="[
      'relative flex items-center gap-1 sm:gap-2 px-3 py-2 border rounded-lg cursor-pointer transition-all duration-200',
      'hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-600',
      isUpdated ? 'bg-blue-50 border-blue-500 text-blue-700' : 'bg-white border-gray-300 hover:border-gray-400'
    ]"
  >
    <FilterModal v-if="showModal" :title="name">
      <slot name="modal"></slot>
    </FilterModal>
    
    <i :class="[icon, 'text-sm sm:text-base']"></i>
    
    <span v-if="!isUpdated" class="text-sm sm:text-base whitespace-nowrap">{{ name }}</span>
    
    <slot v-if="isUpdated" name="update-content"></slot>
    
    <i v-if="!isUpdated" class="fa-solid fa-caret-down text-xs sm:text-sm ml-1"></i>
    
    <i 
      @click.stop="emit('onReset')" 
      v-if="isUpdated" 
      class="fa-solid fa-xmark text-xs sm:text-sm ml-1 p-1 rounded hover:bg-red-100 hover:text-red-600 transition-colors"
    ></i>
  </div>
</template>

<style scoped>

</style>
