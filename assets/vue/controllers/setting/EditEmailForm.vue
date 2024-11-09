<script setup>
  const props = defineProps(["onCancel"])
</script>

<template>
  <div class="p-3">
    <div class="row mb-3">
      <div class="col-sm-6">
        <h6 class="mb-0">Nouvelle adresse e-mail:</h6>
      </div>
      <div class="col-sm-6">
        <input class="form-control text-secondary m-0" type="text">
      </div>
    </div>
    <hr>
    <div class="row mb-3">
      <div class="col-sm-6">
        <h6 class="mb-0">Confirmation de l'e-mail:</h6>
      </div>
      <div class="col-sm-6">
        <input class="form-control text-secondary m-0" type="text">
      </div>
    </div>
    <hr>
    <div class="row mb-3">
      <div class="col-sm-6">
        <h6 class="mb-0">Mot de passe:</h6>
      </div>
      <div class="col-sm-6">
        <input class="form-control text-secondary m-0" type="text">
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <button class="btn btn-primary">Enregistrer</button>
        <button class="btn btn-danger ms-3" @click="props.onCancel">Annuler</button>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>