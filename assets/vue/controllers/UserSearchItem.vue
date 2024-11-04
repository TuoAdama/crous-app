<script setup>
    const {criteria, token, path, onDelete} = defineProps(['criteria', 'token', 'path', 'onDelete'])
    
    function onSubmit(e){
        const formData = new FormData(e.target);
        const body = {};
        formData.forEach((value, key) => body[key] = value);
        
        fetch(path, {
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
            },
            method: "POST",
            body: JSON.stringify(body),
        })
        .then(response  => {
            if (response.status === 200) {
              onDelete();
            }
        })
        .catch(error => {
          alert("Une erreur est survenue");
          window.location.reload();
        })
    }
</script>
<template>
  <div class="col-12 col-md-5 mb-5">
    <div class="card">
      <div class="card-body p-4 d-flex align-items-center">
        <div class="ms-3">
          <div class="display-6">{{ criteria?.location.properties.name}}</div>
          <div class="small text-muted">Ajouté le {{criteria?.updatedAt}}</div>
        </div>
      </div>
      <div class="card-actions p-3 justify-content-between">
        <div class="card-action-buttons">
          <a class="btn btn-text-primary mdc-ripple-upgraded" :href="`/criteria/${criteria.id}`">Modifier</a>
          <form class="d-inline" method="post" @submit.prevent="onSubmit">
            <input type="hidden" name="method" value="DELETE">
            <input type="hidden" name="token" :value="token">
            <input type="hidden" name="id" :value="criteria.id" />
            <button type="submit" onclick="confirm('Vous être sur !')" class="btn btn-text-primary mdc-ripple-upgraded">Supprimer</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>