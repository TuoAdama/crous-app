<script setup>
const props = defineProps({
  title: String,
});
</script>

<template>
  <div @click.stop class="filter-modal flex items-center shadow-lg border rounded-xl bg-white absolute top-full left-0 right-0 sm:left-0 sm:right-auto mt-2 z-50 min-w-full sm:min-w-[200px]">
    <div class="p-4 w-full">
      <h5 class="text-lg font-semibold text-gray-800 mb-3">{{ title }}</h5>
      <slot></slot>
    </div>
  </div>
</template>

<style scoped>

</style>
