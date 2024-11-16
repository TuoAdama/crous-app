<script setup>
    const {criteriaWithResults, token, path, onDelete} = defineProps(['criteriaWithResults', 'token', 'path', 'onDelete'])
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
          console.log(error)
          alert("Une erreur est survenue");
          //window.location.reload();
        })
    }
</script>
<template>
  <div class="col-12 col-md-5 mb-5">
    <div class="card position-relative">
      <a target="_blank" :href="criteriaWithResults.link" v-if="criteriaWithResults.criteria.resultCount">
        <span class="bg-danger text-white end-0 top-0 position-absolute py-1 px-2 border m-2 rounded-2">{{criteriaWithResults.criteria.resultCount}}</span>
      </a>
      <div class="card-body p-2 d-flex align-items-center">
        <div class="ms-3">
          <div class="fs-5">{{ criteriaWithResults.criteria.location.properties.name}}</div>
          <div class="small text-muted">Ajouté le {{criteriaWithResults.criteria.updatedAt}}</div>
          <div class="mt-3">
            <span class="me-2 bg-secondary text-white py-1 px-2 rounded-1" v-for="type in criteriaWithResults.criteria.type">{{type}}</span>
          </div>
        </div>
      </div>
      <div class="card-actions px-2 py-1  justify-content-between">
        <div class="card-action-buttons">
          <a class="btn btn-text-primary mdc-ripple-upgraded" :href="`/criteria/edit/${criteriaWithResults.criteria.id}`">Modifier</a>
          <form class="d-inline" method="post" @submit.prevent="onSubmit">
            <input type="hidden" name="method" value="DELETE">
            <input type="hidden" name="token" :value="token">
            <input type="hidden" name="id" :value="criteriaWithResults.criteria.id" />
            <button type="submit" onclick="confirm('Êtes-vous sûre d\'éffectuer cette opération ?')" class="btn btn-text-primary mdc-ripple-upgraded">Supprimer</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>