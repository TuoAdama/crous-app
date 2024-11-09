<script setup>
import User from "../models/User";

const props = defineProps({
  user: User
})

</script>

<template>
  <div class="card h-100">
    <div class="card-body">
      <h6 class="d-flex align-items-center mb-3">Coordonnées</h6>
      <ul class="list-group list-group-flush">
        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
          <h6 class="mb-0">Email:</h6>
          <span class="text-secondary">{{user.email}}</span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
          <h6 class="mb-0">Téléphone:</h6>
          <span class="text-secondary">{{user.number ?? "Aucun numéro"}}</span>
        </li>
      </ul>
    </div>
  </div>
</template>

<style scoped>

</style>