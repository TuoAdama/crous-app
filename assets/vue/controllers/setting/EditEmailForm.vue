<script setup>
import {inject, ref} from "vue";

  const props = defineProps(["onCancel", "onUpdate"])
  const token = inject("token") ?? "";

  const loading = ref(false)
  const errors = ref({})

  const onSubmit = async (e) => {
    loading.value = true;
    const formData =  new FormData(e.target);
    const body = {};
    formData.forEach((value, key) => body[key] = value.toString());

    const response = await  fetch("/setting/edit/email", {
      method: "POST",
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify(body),
    })
    const result = await response.json();
    if (response.status !==  200) {
      errors.value = result;
    }
    if (response.status === 200) {
      const {user} = result;
      props.onUpdate(user);

    }

    loading.value = false
  }
</script>

<template>
  <form @submit.prevent="onSubmit">
    <div class="alert alert-danger" v-if="'form' in errors">{{errors.form}}</div>
    <div class="p-3">
      <div class="row mb-3">
        <input type="hidden" name="token" :value="token">
        <div class="col-sm-6">
          <h6 class="mb-0">Nouvelle adresse e-mail:</h6>
        </div>
        <div class="col-sm-6">
          <input name="email" class="form-control text-secondary m-0" type="email">
          <small v-if="'email' in errors" class="form-text text-muted text-danger">{{errors.email}}</small>
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
          <small v-if="'password' in errors" class="form-text text-muted text-danger">{{errors.password}}</small>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <button :disabled="loading" type="submit" class="btn btn-primary">{{loading ? 'Chargement...':'Enregistrer'}}</button>
          <button :disabled="loading" class="btn btn-danger ms-3" @click="props.onCancel">Annuler</button>
        </div>
      </div>
    </div>
  </form>
</template>

<style scoped>

</style>