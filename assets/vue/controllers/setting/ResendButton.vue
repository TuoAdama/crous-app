<script setup>
  const props = defineProps(['isVerified', 'isLoading', 'onClick'])
</script>

<template>
  <span><slot></slot></span>
  <span v-if="!isVerified">
      <span> (non vérifié)</span>
      <button @click="onClick" class="btn btn-link text-primary">
        <span v-if="!isLoading">Vérifier ?</span>
        <span v-if="isVerified">envoi ...</span>
      </button>
  </span>
</template>

<style scoped>

</style>