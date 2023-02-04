<template>
  <div class="search-form">
    <form autocomplete="off">
      <input autocomplete="none" type="search" v-model="query" placeholder="Buscar...">
    </form>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps(['collection', 'attribute'])
const emits = defineEmits(['input'])

const query = ref('')
const results = computed(() => {
  if (!props.collection.length || !query.value) return [...props.collection]
  let attribute = (props.attribute) ? props.attribute : 'name'

  return props.collection.filter(f => {
    if (f.hasOwnProperty(attribute)) {
      return f[attribute].toLowerCase().includes(query.value.toLowerCase())
    }
    if (typeof f === 'string') {
      return f.toLowerCase().includes(this.query.toLowerCase())
    }
    return false
  })
})
watch(results, (newValue, oldValue) => {emits('input', results.value)})

</script>

<style lang="css" scoped>
.svg-icon {
  position: absolute;
  z-index: 20;
  left: 13px;
  top: 10px;
  width: 17px;
  height: 17px;
}

.search-form {
  position: relative;
  margin-left: auto;
}

.search-form input {
  font-size: 1em;
  border-radius: 20px;
  padding: 7px 20px 7px 35px;
  border: 1px solid #d4e2e6;
  box-shadow: none;
}
</style>
