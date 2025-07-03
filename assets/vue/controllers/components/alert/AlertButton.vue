<template>
  <button @click.prevent="onAlert" style="background-color: #b91c1c" type="button" class="text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200 w-full sm:w-auto">
    Créer une alerte
  </button>
</template>

<script setup>
function onAlert() {
  const url = window.location.href;
  alert(url)
}
</script>

<style scoped>
</style>
