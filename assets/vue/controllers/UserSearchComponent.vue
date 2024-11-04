<script setup>
import {ref} from "vue";
import UserSearchItem from "./UserSearchItem.vue";

const props = defineProps({
  criteria: [],
  token:  String,
  path: String
})

const criteria = ref(props.criteria);

function onDelete (id){
  criteria.value = criteria.value.filter(i => i.id != id);
}

</script>

<template>
  <div class="col-12 col-md-6 mb-5">
    <UserSearchItem 
        v-for="item in criteria"
        :token="token"
        :criteria="item"
        :path="props.path"
        :onDelete="() => onDelete(item.id)"
    />
  </div>
</template>

<style scoped>

</style>