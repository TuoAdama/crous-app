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
      <div class="text-2xl font-bold text-blue-600">Crousfy</div>
      <!-- Mobile hamburger -->
      <div class="md:hidden">
        <button
          @click="toggleMenu"
          class="text-gray-700 hover:text-gray-900 focus:outline-none"
          aria-label="Ouvrir le menu"
          :aria-expanded="isMenuOpen.toString()"
        >
          <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>
      </div>
      <!-- Desktop menu -->
      <div class="hidden md:flex md:items-center md:space-x-4">
        <a href="#" class="text-gray-600 hover:text-blue-600 py-2">Accueil</a>
        <a v-if="isAuth" href="/profile" class="text-gray-600 hover:text-blue-600 py-2">Profil</a>
        <a v-if="!isAuth" href="/login" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
          Connexion
        </a>
      </div>
    </div>
    <!-- Mobile dropdown -->
    <div v-show="isMenuOpen" class="md:hidden mt-3 border-t border-gray-100 pt-3">
      <div class="flex flex-col space-y-1">
        <a href="#" class="text-gray-700 hover:text-blue-600 py-2">Accueil</a>
        <a v-if="props.isAuth" href="/profile" class="text-gray-700 hover:text-blue-600 py-2">Profil</a>
        <a v-if="!props.isAuth" href="/login" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
          Connexion
        </a>
      </div>
    </div>
  </nav>
</template>

<style scoped>

</style>
