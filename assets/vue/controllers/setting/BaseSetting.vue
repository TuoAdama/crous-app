<script setup>
  import User from "../models/User"
  import {computed, ref} from "vue";

  const props = defineProps({
    user: User,
  })
  const edit = ref(false);
  const user = ref({...props.user})

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
    return (props.user.username === user.value.username) && (props.user.email === user.value.email);
  });

</script>

<template>
  <div class="card mb-3">
    <div class="card-body">
      <div class="row">
        <div class="col-sm-3">
          <h6 class="mb-0">Nom:</h6>
        </div>
        <div v-if="!edit" class="col-sm-9 text-secondary">{{ user.username }}</div>
        <div class="col-sm-9">
          <input v-if="edit" class="form-control text-secondary m-0" type="text" v-model="user.username">
        </div>
      </div>
      <hr>
      <div class="row">
        <div class="col-sm-3">
          <h6 class="mb-0">Email</h6>
        </div>
        <div v-if="!edit" class="col-sm-9 text-secondary">{{user.email}}</div>
        <div class="col-sm-9">
          <input v-if="edit" class="form-control text-secondary m-0" type="text" v-model="user.email">
        </div>
      </div>
      <hr>
      <div class="row">
        <div class="col-sm-12">
          <button v-if="!edit" @click="edit=true" class="btn btn-primary">Modifier</button>
          <button :disabled="disableBtn" v-if="edit" @click="onEdit" class="btn btn-primary">Enregistrer</button>
          <button v-if="edit" @click="onCancel" class="btn btn-danger ms-3">Annuler</button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>