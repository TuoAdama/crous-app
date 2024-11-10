<script setup lang="ts">
  import {PropType} from "vue";

  const props = defineProps({
    onCancel: {
      type: Function as PropType<() => void>,
      required: true
    }
  })
</script>

<template>
  <div class="p-3">
    <div class="row mb-3">
      <div class="col-sm-12">
        <h6 class="mb-0">Numéro de téléphone:</h6>
      </div>
      <div class="col-12 col-lg-8 mt-3">
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