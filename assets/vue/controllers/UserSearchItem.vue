<script setup>
    const {criteriaWithResults, token, path, onDelete} = defineProps(['criteriaWithResults', 'token', 'path', 'onDelete'])
    function onSubmit(id, token){
        fetch(path, {
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
            },
            method: "POST",
            body: JSON.stringify({
                id: id,
                token: token,
                method: "DELETE"
            }),
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
      <button type="button" class="btn btn-danger mdc-ripple-upgraded" data-bs-toggle="modal" :data-bs-target="'#deleteModal-' + criteriaWithResults.criteria.id">Supprimer</button>
    </td>
  </tr>

<div class="modal fade" :id="'deleteModal-' + criteriaWithResults.criteria.id" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Confirmation de suppression</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Êtes-vous sûr de vouloir supprimer ce critère de recherche ?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-danger" @click="onSubmit(criteriaWithResults.criteria.id, token)" data-bs-dismiss="modal">Supprimer</button>
      </div>
    </div>
  </div>
</div>
</template>
