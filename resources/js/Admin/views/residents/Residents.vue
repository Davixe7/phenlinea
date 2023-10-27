<template>
  <div id="residents">
    <extensions-nav :extension="extension" :page="'residents'"/>
    <ResidentsTable
      :residents="localResidents"
      @residentSelection="selected => {resident = selected; modal.show()}"
      @residentDeletion="deleteResident">
    </ResidentsTable>

    <div class="modal fade" tabindex="-1" id="modal">
      <div class="modal-dialog">
        <div class="modal-content">
          <ResidentsForm
            :loading="loading"
            :errors="errors"
            :resident="resident"
            :extension="extension"
            @reset="resetResident()"
            @residentStored="pushResident"
            @residentUpdated="replaceResident" />
        </div>
      </div>
    </div>

    <button
      @click="()=>{resetResident(); modal.show()}"
      class="btn btn-primary fab">
      <i class="material-symbols-outlined">add</i>
    </button>
  </div>
</template>
  
<script setup>
import "vue-toastification/dist/index.css";
import Vue from 'vue'
import ResidentsForm from './ResidentsForm.vue'
import ResidentsTable from './ResidentsTable.vue'
import ExtensionsNav from './../extensions/ExtensionsNav.vue'
import { onMounted, ref, watch, computed } from 'vue'
import { createToastInterface } from "vue-toastification";

const props = defineProps(['residents', 'extension'])
watch(() => props.residents, (newVal, oldVal) => localResidents.value = [...newVal])

var toast             = null;
const modal           = ref(null)
const loading         = ref(false)
const errors          = ref({})
const localResidents  = ref([])
const resident        = ref({})
const defaultResident = ref({
  name: null,
  age: null,
  dni: '',
  is_resident: 1,
  is_owner: 0,
  is_authorized: 0,
  disability: 0,
  card: ''
})

const residentIndex = computed(()=>{
  return resident.value?.id ? localResidents.value.indexOf( resident.value ) : null
})

function deleteResident(res) {
  resident.value = res
  if (!window.confirm('¿Seguro que desea eliminar el registro?')) return
  axios.post(`residents/${res.id}`, { _method: 'DELETE' })
  .then(response => {
    localResidents.value.splice(residentIndex.value, 1)
    toast.success('Eliminado con éxito')
  })
  .catch(error => {
    if (error.response.status == '422') {
      errors.value = error.response.data.errors ? error.response.data.errors : {}
    }
  })
}

function replaceResident(data) {
  localResidents.value.splice(residentIndex.value, 1, data)
  toast.success('Actualizado con éxito')
  modal.value.hide()
}

function pushResident(data) {
  localResidents.value.push(data)
  toast.success('Registrado con éxito')
  modal.value.hide()
}

function resetResident() {
  resident.value = { ...defaultResident.value }
}

onMounted(() => {
  modal.value          = new bootstrap.Modal(document.getElementById('modal'))
  localResidents.value = [...props.residents]
  toast                = createToastInterface({ eventBus: new Vue() })
})
</script>
  
<style lang="scss" scoped>
.fab {
  height: 60px;
  width: 60px;
  position: fixed;
  bottom: 80px;
  right: 80px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
}
.form-check-label {
  color: #0075ff;
}
</style>

