<script setup>
import {ref} from "vue";
import UserSearchItem from "./UserSearchItem.vue";

const props = defineProps({
  criteriaWithResults: [],
  token:  String,
  path: String
})

const criteriaWithResults = ref([...props.criteriaWithResults]);

function onDelete (id){
  criteriaWithResults.value = criteriaWithResults.value.filter(i => i.criteria.id !== id);
}

</script>

<template>
  <div class="row">
    <table class="table table-striped table-hover">
      <thead class="table-dark">
      <tr>
        <th scope="col">Ville</th>
        <th scope="col">Type</th>
        <th scope="col">Actions</th>
      </tr>
      </thead>
      <tbody>
        <UserSearchItem
            v-for="item in criteriaWithResults"
            :token="token"
            :criteriaWithResults="item"
            :path="props.path"
            :onDelete="() => onDelete(item.criteria.id)"
        />
      </tbody>
    </table>
  </div>
</template>

<style scoped>

</style>
