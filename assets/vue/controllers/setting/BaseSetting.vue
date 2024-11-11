<script setup>
  import {computed, inject, ref} from "vue";
  import {MessageType} from "../enum/MessageType";

  const props = defineProps(["user", "onUpdate"]);
  const edit = ref(false);
  const user = ref({...props.user})
  const loading = ref(false);
  const message = ref(null);

  const token = inject('token') ?? "";

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

  const onSubmit = async () => {
    loading.value = true;
    const {username, notifyByEmail, notifyByNumber} = user.value;
    const body = {username, notifyByEmail, notifyByNumber, token};
    const response = await fetch("/setting/edit/base-information", {
      method: "POST",
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify(body),
    });

    const data = await response.json();
    alert(response.status);
    if (response.status === 200) {
      const {user: userUpdated, message: responseMessage} = data;
      user.value = userUpdated;
      props.onUpdate(userUpdated);
      message.value = {
        type: MessageType.SUCCESS,
        content: responseMessage,
      }
    } else {

      message.value = {
        type: MessageType.ERROR,
        content: 'Form is invalid',
      }
    }

    loading.value = false;
  }

</script>

<template>
  <form @submit.prevent="onSubmit">
    <div class="card mb-3">
      <div class="card-body">
        <div :class="`alert alert-${message.type}`" v-if="message != null">{{ message.content }}</div>
        <div class="row">
          <div class="col-sm-3">
            <h6 class="mb-0">Nom:</h6>
          </div>
          <input type="hidden" name="_token" :value="token" />
          <div class="col-sm-9">
            <input name="username" class="form-control text-secondary m-0" type="text" v-model="user.username">
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
            <input name="notifyByEmail" type="checkbox" class="form-check-input" v-model="user.notifyByEmail">
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-10 col-md-3">
            <h6 class="mb-0">Téléphone</h6>
          </div>
          <div class="form-check form-switch mb-3 col col-md-9">
            <input name="notifyByNumber" type="checkbox" class="form-check-input" v-model="user.notifyByNumber">
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-sm-12">
            <button :disabled="disableBtn || loading" @click="onEdit" type="submit" class="btn btn-primary">
              {{loading ? 'Chargement...':'Enregistrer'}}
            </button>
            <button :disabled="loading" v-if="edit" @click="onCancel" class="btn btn-danger ms-3">Annuler</button>
          </div>
        </div>
      </div>
    </div>
  </form>
</template>

<style scoped>

</style>