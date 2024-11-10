<script setup lang="ts">
  import User from "../models/User"
  import {PropType, provide, ref} from "vue";
  import ContactSetting from "./ContactSetting.vue";
  import BaseSetting from "./BaseSetting.vue";

  const props = defineProps({
    user: {
      type: Object as PropType<User>,
      required: true
    },
    token: {
      type: String,
      required: true
    },
  })

  provide<string, string>('token', props.token);

  const user = ref<User>(props.user);
  const edit = ref<boolean>(false);

  const onEdit = () => {
    edit.value = !edit.value;
  }

</script>

<template>
  <div class="container">
    <div class="main-body">
      <nav aria-label="breadcrumb" class="main-breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item"><a href="javascript:void(0)">User</a></li>
          <li class="breadcrumb-item active" aria-current="page">User Profile</li>
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
          <BaseSetting :user/>
          <div class="row gutters-sm">
            <div class="col mb-3">
              <ContactSetting :user="user" />
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<style scoped>

</style>