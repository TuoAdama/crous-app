<script setup>
import defaultImage from './default-image.jpg';

const props = defineProps({
  item: Object,
  idTool: Number,
});

function getPrice(item){
  const minPrice = item.occupationModes[0].rent.min;
  return (minPrice / 100).toFixed(2);
}

function getImageURI(item) {
  if (!item.medias || item.medias.length === 0) {
    return defaultImage;
  }
  const imageName = item.medias[0].src;
  return `https://trouverunlogement.lescrous.fr/media/cache/resolve/preview/${imageName}`;
}

function getHousingURL(id) {
  return `https://trouverunlogement.lescrous.fr/tools/${props.idTool}/accommodations/${id}`;
}

function getRandomBadge() {
  const badges = [
    { text: 'Disponible', color: 'bg-green-100 text-green-800' },
    { text: 'Nouveau', color: 'bg-blue-100 text-blue-800' },
    { text: 'Populaire', color: 'bg-purple-100 text-purple-800' },
    { text: 'Économique', color: 'bg-orange-100 text-orange-800' }
  ];
  return badges[Math.floor(Math.random() * badges.length)];
}

const badge = getRandomBadge();
</script>

<template>
  <div class="card-modern group hover:transform hover:-translate-y-2 transition-all duration-300 overflow-hidden">
    <!-- Image avec overlay -->
    <div class="relative overflow-hidden">
      <img 
        :src="getImageURI(props.item)" 
        :alt="props.item.label" 
        class="w-full h-48 sm:h-56 object-cover group-hover:scale-110 transition-transform duration-500"
      />
      
      <!-- Badge -->
      <div class="absolute top-3 left-3">
        <span :class="`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${badge.color}`">
          {{ badge.text }}
        </span>
      </div>
      
      <!-- Prix overlay -->
      <div class="absolute top-3 right-3">
        <div class="bg-white/90 backdrop-blur-sm rounded-lg px-3 py-1 shadow-lg">
          <span class="text-lg font-bold text-gray-800">{{ getPrice(props.item) }}€</span>
          <span class="text-xs text-gray-600">/mois</span>
        </div>
      </div>
      
      <!-- Gradient overlay au hover -->
      <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
    </div>
    
    <!-- Contenu -->
    <div class="p-6">
      <div class="mb-4">
        <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors duration-200">
          {{ props.item.label }}
        </h3>
        <div class="flex items-center text-gray-600 text-sm mb-3">
          <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
          <span class="line-clamp-1">{{ props.item.location }}</span>
        </div>
      </div>
      
      <!-- Caractéristiques -->
      <div class="flex items-center justify-between mb-4 text-sm text-gray-500">
        <div class="flex items-center">
          <i class="fas fa-bed mr-1"></i>
          <span>Studio</span>
        </div>
        <div class="flex items-center">
          <i class="fas fa-ruler-combined mr-1"></i>
          <span>20m²</span>
        </div>
        <div class="flex items-center">
          <i class="fas fa-wifi mr-1"></i>
          <span>WiFi</span>
        </div>
      </div>
      
      <!-- Bouton d'action -->
      <a 
        target="_blank" 
        :href="getHousingURL(props.item.id)" 
        class="btn-modern w-full justify-center group-hover:bg-gradient-to-r group-hover:from-blue-600 group-hover:to-purple-600 transition-all duration-300"
      >
        <i class="fas fa-external-link-alt mr-2"></i>
        Voir les détails
      </a>
    </div>
  </div>
</template>

<style scoped>
.card-modern {
  background: white;
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.2);
  transition: all 0.3s ease;
}

.card-modern:hover {
  transform: translateY(-8px);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.btn-modern {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border: none;
  color: white;
  padding: 12px 24px;
  border-radius: 8px;
  font-weight: 600;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
  text-decoration: none;
  display: inline-flex;
  align-items: center;
}

.btn-modern:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
  color: white;
  text-decoration: none;
}

.line-clamp-1 {
  overflow: hidden;
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 1;
}

.line-clamp-2 {
  overflow: hidden;
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 2;
}
</style>
