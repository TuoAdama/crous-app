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
        })
    }
</script>
<template>
  <tr>
    <td>{{criteriaWithResults.criteria.location.properties.name}}</td>
    <td>
      <div class="mt-3">
        <span class="me-2 bg-secondary text-white py-1 px-2 rounded-1" v-for="type in criteriaWithResults.criteria.type">{{type}}</span>
      </div>
    </td>
    <td>
      <a class="btn btn-primary btn-sm mx-2" :href="`/criteria/edit/${criteriaWithResults.criteria.id}`">Modifier</a>
      <form class="d-inline" method="post" @submit.prevent="onSubmit">
        <input type="hidden" name="method" value="DELETE">
        <input type="hidden" name="token" :value="token">
        <input type="hidden" name="id" :value="criteriaWithResults.criteria.id" />
        <button type="submit" onclick="confirm('Êtes-vous sûre d\'éffectuer cette opération ?')" class="btn btn-danger mdc-ripple-upgraded">Supprimer</button>
      </form>
    </td>
  </tr>
</template>
