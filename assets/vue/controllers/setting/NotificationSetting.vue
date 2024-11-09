<script setup>
import User from "../models/User"
import {ref} from "vue";

const props = defineProps({
    user: User,
    token: String,
  })

const user = ref(props.user)
</script>

<template>
  <div class="card h-100">
    <div class="card-body">
      <h6 class="d-flex align-items-center mb-3">Notification</h6>
      <div class="form-check form-switch mb-3">
        <div><label for="setting_notifyByEmail">Adresse e-mail</label><input type="checkbox" id="setting_notifyByEmail" class="form-check-input" v-model="user.notifyByEmail"></div>
      </div>
      <div class="form-check form-switch mb-3">
        <div><label for="setting_notifyByEmail">Téléphone</label><input type="checkbox" id="setting_notifyByEmail" class="form-check-input" v-model="user.notifyByNumber"></div>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>