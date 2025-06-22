<script setup>
  import {ref} from "vue";
  import SearchInput from "./SearchInput.vue";
  const isMenuOpen = ref(false);
  const showAdvancedFilters = ref(false);
  const url = "https://trouverunlogement.lescrous.fr/photon/api?limit=18&osm_tag=amenity%3Acollege&osm_tag=amenity%3Alibrary&osm_tag=amenity%3Aschool&osm_tag=amenity%3Auniversity&osm_tag=place%3Acountry&osm_tag=place%3Aregion&osm_tag=place%3Astate&osm_tag=place%3Acity&osm_tag=place%3Atown&osm_tag=place%3Avillage&osm_tag=place%3Ahouse&osm_tag=landuse%3Aresidential";
  const search = ref({
    city: '',
    type: '',
    budgetMin: null,
    budgetMax: null,
    surface: null,
  });
  const listings = ref([
    {
      title: 'Studio cosy à Paris',
      price: 650,
      location: 'Paris, 15e arr.',
      image: 'https://via.placeholder.com/300x200',
    },
    {
      title: 'Colocation à Lyon',
      price: 450,
      location: 'Lyon, 7e arr.',
      image: 'https://via.placeholder.com/300x200',
    },
  ]);

  function toggleMenu() {
    isMenuOpen.value = !isMenuOpen.value;
  }
  function toggleAdvancedFilters() {
    showAdvancedFilters.value = !showAdvancedFilters.value;
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
        <a href="#" class="block md:inline text-gray-600 hover:text-blue-600 py-2">Favoris</a>
        <a href="#" class="block md:inline text-gray-600 hover:text-blue-600 py-2">Profil</a>
        <button class="block md:inline bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 mt-2 md:mt-0">
          Connexion
        </button>
      </div>
    </div>
  </nav>

  <!-- Section de recherche -->
  <div class="bg-gray-100 min-h-[50vh] flex flex-col justify-center items-center text-center px-4">
    <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 mb-6">Trouvez votre logement étudiant idéal</h1>
    <div class="bg-white p-4 sm:p-6 rounded-lg shadow-md w-full max-w-4xl flex flex-col space-y-4">
      <form class="flex flex-col space-y-4">
        <div class="sm:grid sm:grid-cols-2 md:grid-cols-2 gap-4">
          <SearchInput :url />
          <select
              v-model="search.type"
              class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"
          >
            <option value="">Type de logement</option>
            <option value="studio">Studio</option>
            <option value="colocation">Colocation</option>
            <option value="chambre">Chambre</option>
          </select>
        </div>
        <div v-show="showAdvancedFilters" class="flex-col space-y-4 sm:grid sm:grid-cols-2 md:grid-cols-3 gap-4">
          <!-- Champ Budget min avec styles identiques à Budget max -->
          <input
              v-model.number="search.budgetMin"
              type="number"
              placeholder="Budget min (€)"
              class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"
          />
          <!-- Champ Budget max avec styles identiques à Budget min -->
          <input
              v-model.number="search.budgetMax"
              type="number"
              placeholder="Budget max (€)"
              class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"
          />
          <input
              v-model.number="search.surface"
              type="number"
              placeholder="Surface (m²)"
              class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"
          />
        </div>
        <div class="flex flex-col sm:flex-row sm:space-x-4 space-y-4 sm:space-y-0">
          <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200 w-full sm:w-auto">
            Rechercher
          </button>
          <button
              type="button"
              @click="toggleAdvancedFilters"
              class="bg-gray-200 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-300 transition duration-200 w-full sm:w-auto"
          >
            {{ showAdvancedFilters ? 'Masquer filtre' : 'Filtre avancé' }}
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Liste des logements -->
  <div class="p-4 sm:p-8">
    <h2 class="text-xl sm:text-2xl font-bold mb-4">Logements disponibles</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      <div v-for="listing in listings" :key="listing.title" class="bg-white rounded-lg shadow-md overflow-hidden">
        <img :src="listing.image" :alt="listing.title" class="w-full h-40 sm:h-48 object-cover"/>
        <div class="p-4">
          <h3 class="text-base sm:text-lg font-semibold">{{ listing.title }}</h3>
          <p class="text-gray-600 text-sm sm:text-base">{{ listing.location }}</p>
          <p class="text-blue-600 font-bold text-sm sm:text-base">{{ listing.price }} €/mois</p>
          <a href="#" class="mt-2 inline-block bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm sm:text-base">
            Voir détails
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>
