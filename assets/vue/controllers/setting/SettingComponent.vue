<script setup>
  import { provide, ref} from "vue";
  import ContactSetting from "./ContactSetting.vue";
  import BaseSetting from "./BaseSetting.vue";

  const props = defineProps(["user", "token"])

  provide('token', props.token);

  const user = ref({...props.user});
  const edit = ref(false);

  const onUpdateUser = (updatedUser) => {
    user.value = updatedUser;
  }

  const onEdit = () => {
    edit.value = !edit.value;
  }

</script>

<template>
  <div class="container">
    <div class="main-body">
      <nav aria-label="breadcrumb" class="main-breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Accueil</a></li>
          <li class="breadcrumb-item active" aria-current="page">Compte</li>
        </ol>
      </nav>

      <div class="row gutters-sm">
        <div class="col-md-4 mb-3">
          <div class="card">
            <div class="card-body">
              <div class="d-flex flex-column align-items-center text-center">
                <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin" class="rounded-circle" width="150">
                <div class="mt-3">
                  <h4>{{ user.username }}</h4>
                  <p class="text-secondary mb-1">{{ user.email }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-8">
          <BaseSetting :user :on-update="onUpdateUser" />
          <div class="row gutters-sm">
            <div class="col mb-3">
              <ContactSetting :user="user" :on-update="onUpdateUser" />
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<style scoped>

</style>