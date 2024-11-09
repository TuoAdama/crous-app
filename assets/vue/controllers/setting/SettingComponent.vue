<script setup>
  import User from "../models/User"
  import {ref} from "vue";
  import NotificationSetting from "./NotificationSetting.vue";
  import ContactSetting from "./ContactSetting.vue";
  import BaseSetting from "./BaseSetting.vue";

  const props = defineProps({
    user: User,
    token: String,
  })

  const user = ref(props.user);
  const edit = ref(false);

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
          <BaseSetting :user :token/>
          <div class="row gutters-sm">
            <div class="col-sm-6 mb-3">
              <NotificationSetting :user :token />
            </div>
            <div class="col-sm-6 mb-3">
              <ContactSetting :user :token />
            </div>
          </div>



        </div>
      </div>

    </div>
  </div>
</template>

<style scoped>

</style>