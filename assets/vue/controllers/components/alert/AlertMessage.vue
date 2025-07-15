<script setup>
import {computed} from "vue";
const {message, type} = defineProps(['message', 'type'])

const color = computed(() => type === 'success' ? 'green':'red')

</script>

<template>
  <div :class="'my-2 w-full flex items-start  border border-'+color+'-400 bg-'+color+'-100 text-'+color+'-700 px-4 py-2 rounded justify-between'" role="alert">
    <div class="flex">
      <span class="block sm:inline">{{ message }}</span>
    </div>
    <button type="button" onclick="this.parentElement.style.display='none'" :class="'text-'+color+'-500 hover:text-'+color+'-700 ml-4'">
      <svg class="w-5 h-5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
        <title>Fermer</title>
        <path d="M14.348 5.652a1 1 0 00-1.414 0L10 8.586 7.066 5.652a1 1 0 10-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 001.414 1.414L10 11.414l2.934 2.934a1 1 0 001.414-1.414L11.414 10l2.934-2.934a1 1 0 000-1.414z"/>
      </svg>
    </button>
  </div>
</template>

<style scoped>

</style>
