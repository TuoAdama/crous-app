<script setup>

</script>

<template>
  <form>
    <slot></slot>
    <div class="p-3">
      <div class="row mb-3">
        <div class="col-sm-12">
          <h6 class="mb-0 text-center">Entrez le code vérfication:</h6>
        </div>
        <div class="col-12 col-lg-12 mt-3 text-center">
          <div class="d-flex justify-content-center w-100">
            <input name="number" class="col-3 ms-1 w-50 form-control text-secondary m-0" type="number" required>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12 text-center">
          <button class="btn btn-primary">Valider</button>
        </div>
      </div>
    </div>
  </form>
</template>

<style scoped>

</style>