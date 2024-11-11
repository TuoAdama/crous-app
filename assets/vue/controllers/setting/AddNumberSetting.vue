<script setup>
import {inject, ref} from "vue";

  const props = defineProps(["onCancel"])
  const token = inject("token") ?? "";
  const loading = ref(false);

  const number = ref("");
  const onSubmit = (e) => {
    loading.value = true;

  }

</script>

<template>
  <form method="post" action="/setting/resend/number/verification">
    <div class="p-3">
      <div class="row mb-3">
        <div class="col-sm-12">
          <h6 class="mb-0">Numéro de téléphone:</h6>
        </div>
        <input type="hidden" name="token" :value="token" />
        <div class="col-12 col-lg-8 mt-3">
          <div class="d-flex align-items-center border input-group-text ps-2 pe-0 py-0">
            <div>+33</div>
            <input name="number" v-model="number" class="ms-1 h-100 form-control text-secondary m-0" type="text" pattern="0[1-9]{1}[0-9]{8}" required>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <button :disabled="loading" type="submit" class="btn btn-primary">Enregistrer</button>
          <button class="btn btn-danger ms-3" @click="props.onCancel" v-if="!loading">Annuler</button>
        </div>
      </div>
    </div>
  </form>
</template>

<style scoped>

</style>