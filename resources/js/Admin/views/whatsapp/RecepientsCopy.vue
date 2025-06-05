<script setup>
import { computed, onMounted, ref, watch, defineEmits } from 'vue';
const props  = defineProps(['extensions', 'value'])
const emits = defineEmits(['update:value'])

const search       = ref('')
const activeFilter = ref(0)
const selected     = ref([])
const togglerCheck = ref(null)

onMounted(()=>{
  selected.value = [...props.value]
})

watch(selected, () => emits('input', selected.value))

function toggleSelection(){
  if( selected.value.length == 0 ){
    selected.value = [...results.value].map(e => e.id)
    return
  }
  selected.value = []
}

const selectedAll = computed(()=>{
  if( selected.value.length == 0 ) return 0
  if( selected.value.length == results.value.length ) return 1
  togglerCheck.value.checked = true
  return ''
})

const results = computed(()=>{
  let res = [...props.extensions]

  if( activeFilter.value == 1 ){
    res = [...props.extensions.filter(e => e.owner_phone)]
  }

  if( search.value != '' ){
    res = res.filter(e => e.name.includes(search.value))
  }

  return res;
})
</script>

<template>
  <div class="d-flex flex-column" style="flex: 1 1 auto; overflow: hidden;">
    <div class="py-3" style="flex: 0 0 auto;">
      <input
        type="search"
        class="form-control form-control-lg"
        placeholder="Buscar apartamento..."
        style="font-size: 1rem;"
        v-model="search">
    </div>

    <div class="d-flex align-items-center px-3 pb-3" style="flex: 0 0 auto;">
      <div class="form-check">
        <input
          @click="toggleSelection"
          class="form-check-input"
          type="checkbox"
          value=""
          :indeterminate="selectedAll === ''"
          ref="togglerCheck"
        >
      </div>
      <div class="ms-auto">
        <ul class="nav nav-pills">
          <li class="nav-item">
            <a
              @click="activeFilter=0"
              class="nav-link nav-link-secondary me-2"
              :class="{active: activeFilter==0}"
              href="#">
              Todos
            </a>
          </li>
          <li class="nav-item">
            <a
              @click="activeFilter=1"
              class="nav-link nav-link-secondary"
              :class="{active: activeFilter==1}"
              href="#">
              Propietarios
            </a>
          </li>
        </ul>
      </div>
    </div>

    <ul class="list-group" style="flex: 1 1 auto; overflow: auto;">
      <li
        v-for="extension in results"
        :key="extension.id"
        class="list-group-item">
        <div class="form-check d-flex align-items-center">
          <input class="form-check-input" type="checkbox" v-model="selected" :value="extension.id">
          <label class="form-check-label" for="flexCheckIndeterminate">
            {{ extension.name }}
          </label>
        </div>
      </li>
    </ul>
  </div>
</template>

<style>
.nav-link {
  font-size: .9rem;
  padding: .35rem .75rem;
  border-radius: 5px;
}
.nav-link-secondary.active {
  background: var(--bs-secondary) !important;
}
.form-check-label {
  padding-top: 2px;
}
.form-check-input {
  padding-top: 0;
  margin-top: 0;
}
</style>