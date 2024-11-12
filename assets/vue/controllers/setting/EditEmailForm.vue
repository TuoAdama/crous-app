<script setup>
import {inject, ref} from "vue";
import {ErrorMessage} from "../enum/ErrorMessage";
import {MessageType} from "../enum/MessageType";

  const props = defineProps(["user", "onCancel", "onUpdate"])
  const token = inject("token") ?? "";

  const loading = ref(false)
  const errors = ref({})
  const email = ref();
  const confirmEmail = ref();
  const message = ref({});

  const onSubmit = async (e) => {
    const errorMessage = getFormMessageError();
    if (errorMessage) {
      message.value.type = MessageType.ERROR;
      message.value.content = errorMessage;
      return;
    }
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
    if (response.status === 200) {
      const {user} = result;
      props.onUpdate(user);
    } else if (response.status ===  422) {
      if (result.errors) {
        errors.value = result.errors;
      }
      if (result.detail) {
        message.value.type = MessageType.SUCCESS;
        message.value.content = result.detail;
      }
    }

    loading.value = false
  }

  const getFormMessageError = () => {
    if (props.user.email === email.value) {
      return ErrorMessage.DIFFERENT_EMAIL_ERROR_MESSAGE;
    }
    if (email.value !== confirmEmail.value) {
      return ErrorMessage.CONFIRM_EMAIL_NOT_IDENTICAL_ERROR_MESSAGE;
    }
    return null;
  }

</script>

<template>
  <form @submit.prevent="onSubmit">
    <div :class="`alert alert-${message.type}`" v-if="message.type">{{message.content}}</div>
    <div class="p-3">
      <div class="row mb-3">
        <input type="hidden" name="token" :value="token">
        <div class="col-sm-6">
          <h6 class="mb-0">Nouvelle adresse e-mail:</h6>
        </div>
        <div class="col-sm-6">
          <input v-model="email" name="email" class="form-control text-secondary m-0" type="email" required>
          <small v-if="'email' in errors" class="form-text text-muted text-danger">{{errors.email}}</small>
        </div>
      </div>
      <hr>
      <div class="row mb-3">
        <div class="col-sm-6">
          <h6 class="mb-0">Confirmation de l'e-mail:</h6>
        </div>
        <div class="col-sm-6">
          <input v-model="confirmEmail" name="confirmEmail" class="form-control text-secondary m-0" type="email" required>
        </div>
      </div>
      <hr>
      <div class="row mb-3">
        <div class="col-sm-6">
          <h6 class="mb-0">Mot de passe:</h6>
        </div>
        <div class="col-sm-6">
          <input class="form-control text-secondary m-0" name="password" type="password" required>
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