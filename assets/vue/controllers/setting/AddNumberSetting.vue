<script setup>
import {inject, ref} from "vue";
import {MessageType} from "../enum/MessageType";
import NumerVerificationForm from "./NumerVerificationForm.vue";

  const props = defineProps(["onCancel"])
  const token = inject("token") ?? "";
  const loading = ref(false);
  const showVerificationForm = ref(false);
  const message = ref({});

  const number = ref("");
  const onSubmit = async (e) => {
    loading.value = true;
    const response = await fetch("/setting/resend/number/verification", {
      method: "POST",
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify({token, number: number.value}),
    })
    const data = await response.json();
    let responseType = null;

    let responseMessage = data.message;
    if (response.status === 200) {
        showVerificationForm.value = true;
        responseType = MessageType.SUCCESS;
    } else if (response.status === 422) {
      responseType = MessageType.ERROR;
    } else if (response.status === 500) {
      responseType = MessageType.ERROR;
      responseMessage = "Une erreur est survenue, veuillez ressayer plus tard";
    }
    message.value.type = responseType;
    message.value.content = responseMessage;
    loading.value = false;
  }

</script>

<template>
  <form @submit.prevent="onSubmit" v-if="!showVerificationForm">
    <div :class="`alert alert-${message.type}`" v-if="message.type">{{ message.content }}</div>
    <div class="p-3">
      <div class="row mb-3">
        <div class="col-sm-12">
          <h6 class="mb-0">Numéro de téléphone:</h6>
        </div>
        <input type="hidden" name="token" :value="token" />
        <div class="col-12 col-lg-8 mt-3">
          <div class="d-flex align-items-center border input-group-text ps-2 pe-0 py-0">
            <div>+33</div>
            <input name="number" v-model="number" class="ms-1 h-100 form-control text-secondary m-0" type="text" pattern="0[1-9]{1}[0-9]{8}" required>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <button :disabled="loading" type="submit" class="btn btn-primary">
            {{loading ? 'Chargement...': 'Enregistrer'}}
          </button>
          <button class="btn btn-danger ms-3" @click="props.onCancel" v-if="!loading">Annuler</button>
        </div>
      </div>
    </div>
  </form>
  <NumerVerificationForm v-else>
    <div :class="`alert alert-${message.type}`" v-if="message.type">{{ message.content }}</div>
  </NumerVerificationForm>
</template>

<style scoped>

</style>