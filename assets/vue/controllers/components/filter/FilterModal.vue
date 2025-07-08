<script setup>
  const props = defineProps({
    title: Object,
  });
</script>

<template>
  <div @click.stop class="flex items-center shadow-md border min-w-18 rounded-xl bg-white justify-center bg-gray-100 absolute top-full left-0 mt-2">
    <div class="p-3 w-full">
      <h5 class="text-xl font-semibold text-gray-800 mb-3">{{ title }}</h5>
      <slot></slot>
    </div>
  </div>
</template>

<style scoped>

</style>
