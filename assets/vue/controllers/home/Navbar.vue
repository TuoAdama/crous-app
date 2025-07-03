<script setup>
import {ref} from 'vue'
const isMenuOpen = ref(false);

const props = defineProps({
  isAuth: {
    type: Boolean,
    default: false
  }
});

function toggleMenu() {
  isMenuOpen.value = !isMenuOpen.value;
}
</script>

<template>
  <nav class="bg-white shadow-md p-4">
    <div class="flex justify-between items-center max-w-7xl mx-auto">
      <div class="text-2xl font-bold text-blue-600">StudentStay</div>
      <div class="md:hidden">
        <button @click="toggleMenu" class="text-gray-600">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>
      </div>
      <div :class="['md:flex md:space-x-4 mt-4 md:mt-0', { 'hidden': !isMenuOpen, 'block': isMenuOpen }]">
        <a href="#" class="block md:inline text-gray-600 hover:text-blue-600 py-2">Accueil</a>
        <a href="#" class="block md:inline text-gray-600 hover:text-blue-600 py-2">Rechercher</a>
        <a v-if="isAuth" href="#" class="block md:inline text-gray-600 hover:text-blue-600 py-2">Favoris</a>
        <a v-if="isAuth" href="/profile" class="block md:inline text-gray-600 hover:text-blue-600 py-2">Profil</a>
        <button v-if="!isAuth" class="block md:inline bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 mt-2 md:mt-0">
          Connexion
        </button>
      </div>
    </div>
  </nav>
</template>

<style scoped>

</style>
