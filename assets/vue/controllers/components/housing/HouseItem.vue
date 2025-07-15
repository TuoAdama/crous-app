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

</script>

<template>
  <div class="bg-white rounded-lg shadow-md overflow-hidden">
    <img :src="getImageURI(props.item)" :alt="props.item.label" class="w-full h-40 sm:h-48 object-cover"/>
    <div class="p-4">
      <h3 class="text-base sm:text-lg font-semibold">{{ props.item.label }}</h3>
      <p class="text-gray-600 text-sm sm:text-base">{{ props.item.location }}</p>
      <p class="text-blue-600 font-bold text-sm sm:text-base">{{ getPrice(props.item) }} €/mois</p>
      <a target="_blank" :href="getHousingURL(props.item.id)" class="mt-2 inline-block bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm sm:text-base">
        Voir détails
      </a>
    </div>
  </div>
</template>

<style scoped>

</style>
