<script setup>
import User from "../models/User";
import {ref} from "vue";
import EditEmailForm from "./EditEmailForm.vue";

const props = defineProps({
  user: User
})

const edit = ref(false);

</script>

<template>
  <div class="card h-100">
    <div class="card-body">
      <template v-if="!edit">
        <h6 class="d-flex align-items-center mb-3">Coordonnées</h6>
        <ul class="list-group list-group-flush">
          <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
            <h6 class="mb-0">Email:</h6>
            <span class="text-secondary">
            {{user.email}}

            <button class="ms-3 btn btn-secondary" @click="edit = true">Modifier</button>
          </span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
            <h6 class="mb-0">Téléphone:</h6>
            <span class="text-secondary">
            {{user.number ?? "Aucun numéro"}}
            <button class="ms-3 btn btn-secondary">
              {{user.number ? 'Modifier': 'Ajouter'}}
            </button>
          </span>
          </li>
        </ul>
      </template>
      <EditEmailForm v-if="edit" :onCancel="() => edit = false"/>
    </div>
  </div>
</template>

<style scoped>

</style>