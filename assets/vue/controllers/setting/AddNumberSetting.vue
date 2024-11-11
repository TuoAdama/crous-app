<script setup>
  const props = defineProps(["onCancel"])
</script>

<template>
  <form action="">
    <div class="p-3">
      <div class="row mb-3">
        <div class="col-sm-12">
          <h6 class="mb-0">Numéro de téléphone:</h6>
        </div>
        <div class="col-12 col-lg-8 mt-3">
          <div class="d-flex align-items-center border input-group-text ps-2 pe-0 py-0">
            <div>+33</div>
            <input class="ms-1 h-100 form-control text-secondary m-0" type="text" pattern="0[1-9]{1}[0-9]{8}" required>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <button type="submit" class="btn btn-primary">Enregistrer</button>
          <button class="btn btn-danger ms-3" @click="props.onCancel">Annuler</button>
        </div>
      </div>
    </div>
  </form>
</template>

<style scoped>

</style>