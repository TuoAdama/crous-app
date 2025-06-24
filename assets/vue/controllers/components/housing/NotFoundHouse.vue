<script setup lang="ts">

</script>

<template>
  <div class="flex flex-col items-center justify-center min-h-[50vh] sm:min-h-[60vh] bg-gray-50 p-4 sm:px-6 md:p-8 rounded-lg">
    <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-400 mb-3 sm:mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
    </svg>
    <h2 class="text-xl sm:text-2xl md:text-3xl font-semibold text-gray-800 mb-2 sm:mb-3">Aucun logement trouvé</h2>
    <p class="text-sm sm:text-base text-gray-600 text-center max-w-xs sm:max-w-md mb-4 sm:mb-6">
      Désolé, nous n'avons trouvé aucun logement correspondant à vos critères. Essayez de modifier vos filtres ou élargissez votre recherche.
    </p>
    <button
        @click="$emit('reset-filters')"
        class="px-3 py-2 sm:px-4 sm:py-2 bg-blue-600 text-white text-sm sm:text-base rounded-md hover:bg-blue-700 transition-colors"
    >
      Réinitialiser les filtres
    </button>
  </div>
</template>

<style scoped>

</style>
