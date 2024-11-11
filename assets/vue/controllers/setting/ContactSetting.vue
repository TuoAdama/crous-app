<script setup>
import User from "../models/User";
import {ref} from "vue";
import EditEmailForm from "./EditEmailForm.vue";
import AddNumberSetting from "./AddNumberSetting.vue";

const props = defineProps(["user", "onUpdate"]);

const message = ref("");
const edit = ref(false);
const isAddNumber = ref(false);
const isEditEmail = ref(false);
const user = ref({...props.user});

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

</script>

<template>
  <div class="card h-100">
    <div class="card-body">
      <template v-if="!isEditEmail && !isAddNumber">
        <div class="alert alert-warning" v-if="message.length">{{message}}</div>
        <h6 class="d-flex align-items-center mb-3">Coordonnées</h6>
        <ul class="list-group list-group-flush">
          <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
            <h6 class="mb-0">Email:</h6>
            <span class="text-secondary">
            {{user.email}}
            <span v-if="!user.emailIsVerified">(non vérifiée)</span>
            <button class="ms-3 btn btn-secondary" @click="isEditEmail = true">Modifier</button>
          </span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
            <h6 class="mb-0">Téléphone:</h6>
            <span class="text-secondary">
            {{user.number ?? "Aucun numéro"}}
            <button v-if="user.number" class="ms-3 btn btn-secondary" >Modifier</button>
            <button v-if="!user.number" class="ms-3 btn btn-secondary" @click="onAddNumber">Ajouter</button>
          </span>
          </li>
        </ul>
      </template>
      <EditEmailForm v-if="isEditEmail && !isAddNumber" :on-update="onUpdateEmail" :on-cancel="() => isEditEmail = false"/>
      <AddNumberSetting v-if="isAddNumber && !isEditEmail" :on-cancel="() => isAddNumber = false"/>
    </div>
  </div>
</template>

<style scoped>

</style>