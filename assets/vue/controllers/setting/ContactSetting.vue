<script setup>
import {inject, ref} from "vue";
import EditEmailForm from "./EditEmailForm.vue";
import AddNumberSetting from "./AddNumberSetting.vue";
import ResendButton from "./ResendButton.vue";

const props = defineProps(["user", "onUpdate"]);

const message = ref("");
const edit = ref(false);
const isAddNumber = ref(false);
const isEditEmail = ref(false);
const user = ref({...props.user});
const loading = ref(false);
const isSendNumberVerificationLoading = ref(false);

const token = inject("token") ?? "";

const onAddNumber = () => {
  isAddNumber.value = true;
  edit.value = true;
}

const onUpdateEmail = (userUpdated) => {
  user.value = userUpdated;
  props.onUpdate(userUpdated);
  message.value = "Un mail de confirmation vous été envoyé !";
  isEditEmail.value = false;
}

const onResendVerification = async () => {
  loading.value = true;
  const response = await fetch("/setting/resend/email/verification");
  if (response.ok) {
    const data = await response.json();
    message.value = data.message;
    loading.value = false;
  }
}

const onSendNumberVerification = async () => {
  window.location.href = "/setting/verification/resend";
}

</script>

<template>
  <div class="card h-100">
    <div class="card-body">
      <template v-if="!isEditEmail && !isAddNumber">
        <div class="alert alert-warning" v-if="message.length">{{ message }}</div>
        <h6 class="d-flex align-items-center mb-3">Coordonnées</h6>
        <ul class="list-group list-group-flush">
          <li class="list-group-item d-flex flex-column flex-lg-row justify-content-start justify-content-lg-between align-items-lg-center flex-wrap">
            <h6 class="mb-0">Email:</h6>
            <div class="d-flex flex-column flex-lg-row">
              <span class="text-secondary my-2">
                <ResendButton
                    :is-loading="loading"
                    :is-verified="user.emailIsVerified"
                    :on-click="onResendVerification"
                >
                  {{ user.email }}
                </ResendButton>
            </span>
              <div>
                <button class="ms-O  ms-lg-3 btn btn-secondary" @click="isEditEmail = true">Modifier</button>
              </div>
            </div>
          </li>
          <li class="mt-2 mt-md-0 list-group-item d-flex flex-column flex-lg-row justify-content-start justify-content-lg-between align-items-start align-items-lg-center flex-wrap">
            <h6 class="mb-0">Téléphone:</h6>
            <div class="d-flex flex-column flex-lg-row">
              <span class="text-secondary">
              <ResendButton
                  v-if="user.number"
                  :is-loading="isSendNumberVerificationLoading"
                  :is-verified="user.numberIsVerified"
                  :on-click="onSendNumberVerification"
              >
                {{user.number}}
              </ResendButton>
                <span v-else>Aucun numéro</span>
            </span>
              <div>
                <button class="ms-O  ms-lg-3 btn btn-secondary" @click="onAddNumber">Modifier</button>
              </div>
            </div>
          </li>
        </ul>
      </template>
      <EditEmailForm v-if="isEditEmail && !isAddNumber" :user="user" :on-update="onUpdateEmail"
                     :on-cancel="() => isEditEmail = false"/>
      <AddNumberSetting v-if="isAddNumber && !isEditEmail" :on-cancel="() => isAddNumber = false"/>
    </div>
  </div>
</template>

<style scoped>

</style>