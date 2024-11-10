<script setup lang="ts">
  import {PropType, inject} from "vue";

  const props = defineProps({
    onCancel: {
      type: Function as PropType<() => void>,
      required: true,
    }
  })
  const token: string = inject<string>("token");

  const onSubmit = (e) => {
    const formData =  new FormData(e.target);
    const body = {};
    formData.forEach((value, key) => body[key] = value);
  }
</script>

<template>
  <form @submit.prevent="onSubmit">
    <div class="p-3">
      <div class="row mb-3">
        <input type="hidden" name="token" :value="token">
        <div class="col-sm-6">
          <h6 class="mb-0">Nouvelle adresse e-mail:</h6>
        </div>
        <div class="col-sm-6">
          <input name="email" class="form-control text-secondary m-0" type="email">
        </div>
      </div>
      <hr>
      <div class="row mb-3">
        <div class="col-sm-6">
          <h6 class="mb-0">Confirmation de l'e-mail:</h6>
        </div>
        <div class="col-sm-6">
          <input name="confirmEmail" class="form-control text-secondary m-0" type="email">
        </div>
      </div>
      <hr>
      <div class="row mb-3">
        <div class="col-sm-6">
          <h6 class="mb-0">Mot de passe:</h6>
        </div>
        <div class="col-sm-6">
          <input class="form-control text-secondary m-0" name="password" type="password">
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