<script setup>
import User from "../models/User";
import {ref} from "vue";
import EditEmailForm from "./EditEmailForm.vue";
import AddNumberSetting from "./AddNumberSetting.vue";

const props = defineProps({
  user: User
})

const edit = ref(false);
const isAddNumber = ref(false);
const isEditEmail = ref(false);

const onAddNumber = () => {
  isAddNumber.value = true;
  edit.value = true;
}

</script>

<template>
  <div class="card h-100">
    <div class="card-body">
      <template v-if="!isEditEmail && !isAddNumber">
        <h6 class="d-flex align-items-center mb-3">Coordonnées</h6>
        <ul class="list-group list-group-flush">
          <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
            <h6 class="mb-0">Email:</h6>
            <span class="text-secondary">
            {{user.email}}

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
      <EditEmailForm v-if="isEditEmail && !isAddNumber" :on-cancel="() => isEditEmail = false"/>
      <AddNumberSetting v-if="isAddNumber && !isEditEmail" :on-cancel="() => isAddNumber = false"/>
    </div>
  </div>
</template>

<style scoped>

</style>