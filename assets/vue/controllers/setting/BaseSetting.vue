<script setup>
  import User from "../models/User"
  import {ref} from "vue";

  const props = defineProps({
    user: User,
  })
  const edit = ref(false);
  const user = ref(props.user)

  const onEdit = () => {
    edit.value = !edit.value;
  }
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
          <input v-if="edit" class="form-control text-secondary m-0" type="text" :value="user.username">
        </div>
      </div>
      <hr>
      <div class="row">
        <div class="col-sm-3">
          <h6 class="mb-0">Email</h6>
        </div>
        <div v-if="!edit" class="col-sm-9 text-secondary">{{user.email}}</div>
        <div class="col-sm-9">
          <input v-if="edit" class="form-control text-secondary m-0" type="text" :value="user.email">
        </div>
      </div>
      <hr>
      <div class="row">
        <div class="col-sm-12">
          <button @click="onEdit" class="btn btn-primary">{{edit ? 'Enregistrer':'Modifier'}}</button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>