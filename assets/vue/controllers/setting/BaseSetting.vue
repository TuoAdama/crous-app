<script setup lang="ts">
  import User from "../models/User"
  import {computed, PropType, ref} from "vue";

  const props = defineProps({
    user: {
      type: Object as PropType<User>,
      required: true
    },
  })
  const edit = ref<boolean>(false);
  const user = ref<User>({...props.user})

  const onEdit = () => {
    if (!edit.value) {
      edit.value = true;
      return;
    }
    edit.value = false;
  }

  const onCancel = () => {
    edit.value = false;
    user.value = props.user;
  }

  const disableBtn = computed(() => {
    return (props.user.username === user.value.username) && (props.user.email === user.value.email)
     && (props.user.notifyByEmail === user.value.notifyByEmail) && (props.user.notifyByNumber === user.value.notifyByNumber)
  });

</script>

<template>
  <div class="card mb-3">
    <div class="card-body">
      <div class="row">
        <div class="col-sm-3">
          <h6 class="mb-0">Nom:</h6>
        </div>
        <div class="col-sm-9">
          <input class="form-control text-secondary m-0" type="text" v-model="user.username">
        </div>
      </div>
      <hr>
      <div class="row my-3">
        <h5 class="text-primary">Notification</h5>
      </div>
      <div class="row">
        <div class="col-10 col-md-3">
          <h6 class="mb-0">Adresse e-mail</h6>
        </div>
        <div class="form-check form-switch mb-3 col col-md-9">
          <input type="checkbox" class="form-check-input" v-model="user.notifyByEmail">
        </div>
      </div>
      <hr>
      <div class="row">
        <div class="col-10 col-md-3">
          <h6 class="mb-0">Téléphone</h6>
        </div>
        <div class="form-check form-switch mb-3 col col-md-9">
          <input type="checkbox" class="form-check-input" v-model="user.notifyByNumber">
        </div>
      </div>
      <hr>
      <div class="row">
        <div class="col-sm-12">
          <button :disabled="disableBtn" @click="onEdit" class="btn btn-primary">Enregistrer</button>
          <button v-if="edit" @click="onCancel" class="btn btn-danger ms-3">Annuler</button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>