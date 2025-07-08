<script setup>
const props = defineProps({
  isAuth: Object,
});
function onAlert() {
  const url = window.location.href;
}

function authWhenNotAuth() {
  window.location.href = '/alert';
}
</script>

<template>
  <button @click.prevent="isAuth ? onAlert : authWhenNotAuth " style="background-color: #b91c1c" type="button" class="text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200 w-full sm:w-auto">
    Créer une alerte
  </button>
</template>

<style scoped>
</style>
