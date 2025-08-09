<script setup>
import {ref} from "vue";
import UserSearchItem from "./UserSearchItem.vue";

const props = defineProps({
  criteriaWithResults: [],
  token:  String,
  path: String
})

const criteriaWithResults = ref([...props.criteriaWithResults.items]);
const pagination = ref(props.criteriaWithResults.pagination);

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
    <div class="navigation text-primary">
        <ul class="pagination">
            <li class="page-item text-primary" :class="{disabled: pagination.current === 1}">
                <a class="page-link" :href="`?page=${pagination.current - 1}`">Previous</a>
            </li>
            <li class="page-item" v-for="page in pagination.pagesInRange" :class="{active: page === pagination.current}">
                <a class="page-link" :href="`?page=${page}`">{{ page }}</a>
            </li>
            <li class="page-item" :class="{disabled: pagination.current === pagination.last}">
                <a class="page-link" :href="`?page=${pagination.current + 1}`">Next</a>
            </li>
        </ul>
    </div>
  </div>
</template>

<style scoped>

</style>
