<script setup>
import {ref} from "vue";
import UserSearchItem from "./UserSearchItem.vue";

const props = defineProps({
  criteriaWithResults: [],
  token:  String,
  path: String
})

const criteriaWithResults = ref(props.criteriaWithResults);

function onDelete (id){
  criteriaWithResults.value = criteriaWithResults.value.filter(i => i.id != id);
}

</script>

<template>
  <div class="row">
    <UserSearchItem
        v-for="item in criteriaWithResults"
        :token="token"
        :criteriaWithResults="item"
        :path="props.path"
        :onDelete="() => onDelete(item.id)"
    />
  </div>
</template>

<style scoped>

</style>