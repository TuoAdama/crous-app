<script setup>
import {onMounted} from "vue";

const props = defineProps({
  criteria: [],
  token:  null,
  path: null
})

function onSubmit (e){
  const formData = new FormData(e.target);
  const body = {};
  formData.forEach((value, key) => body[key] = value);
  fetch(props.path, {
    method: "POST",
    body: JSON.stringify(body)
  }).then(response  => response.json())
      .then(response  => console.log(response))
}

</script>

<template>
  <div class="col-12 col-md-6 mb-5">
    <div class="card" v-for="item in criteria">
      <div class="card-body p-4 d-flex align-items-center">
        <div class="ms-3">
          <div class="display-6">{{ item.location.properties.name}}</div>
          <div class="small text-muted">Ajouté le {{item.updatedAt}}</div>
        </div>
      </div>
      <div class="card-actions p-3 justify-content-between">
        <span class="badge bg-dark ms-2">Default</span>
        <div class="card-action-buttons">
          <button class="btn btn-text-primary mdc-ripple-upgraded" type="button">Modifier</button>
          <form class="d-inline" method="post" @submit.prevent="onSubmit">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" :value="token">
            <input type="hidden" name="id" :value="item.id" />
            <button type="submit" onclick="confirm('Vous être sur !')" class="btn btn-text-primary mdc-ripple-upgraded">Supprimer</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>