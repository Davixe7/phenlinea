<template>
  <div class="form-section">
    <h4>
      <i class="material-symbols-outlined">account_circle</i>
      Información personal
    </h4>

    <Camera v-if="camera" @cameraClosed="camera = false" @pictureTaken="updatePicture"></Camera>

    <form ref="storeResidentForm" id="create-resident-form"
      @submit.prevent="resident.id ? updateResident() : storeResident()">
      <div class="form-group">
        <label for="type" class="d-block">Tipo de persona</label>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="checkbox" id="inlineCheckbox1" v-model.number="resident.is_owner"
            :true-value="1" :false-value="0" name="tipo de residente">
          <label class="form-check-label" for="inlineCheckbox1">Propietario</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="checkbox" id="inlineCheckbox2" v-model.number="resident.is_resident"
            name="tipo de residente" :true-value="1" :false-value="0">
          <label class="form-check-label" for="inlineCheckbox2">Residente</label>
        </div>
        <div class="form-check form-check-inline me-0">
          <input class="form-check-input" type="checkbox" id="is_authorized" v-model.number="resident.is_authorized"
            :true-value="1" :false-value="0">
          <label class="form-check-label" for="is_authorized">Autorizado</label>
        </div>
        <span class="invalid-feedback text-right" :class="{ 'd-block': !typeChecked }">
          Por favor seleccione el tipo de residente
        </span>
      </div>

      <div class="row">
        <div class="form-group col-8">
          <label for="name">Nombres <span class="text-danger">*</span></label>
          <input type="text" class="form-control" v-model="resident.name" maxlength="120"
            :class="{ 'is-invalid': errors.name }" required>
          <div v-if="errors.name" class="invalid-feedback">{{ errors.name[0] }}</div>
        </div>
        <div class="form-group col-4">
          <label for="age">Edad <span class="text-danger">*</span></label>
          <input type="number" class="form-control" v-model="resident.age" min="1" max="120" required>
        </div>
      </div>

      <div class="row">
        <div class="form-group col-12">
          <label for="dni">Cédula <span class="text-danger">*</span></label>
          <input type="tel" class="form-control" v-model="resident.dni" minlength="5" maxlength="11"
            :class="{ 'is-invalid': errors.dni }">
          <div v-if="errors.dni" class="invalid-feedback">{{ errors.dni[0] }}</div>
        </div>

        <div class="form-group col-12">
          <label for="card">Tarjeta</label>
          <input
            type="tel"
            class="form-control"
            :class="{'is-invalid': errors && errors.card}"
            v-model="resident.card"
            minlength="5"
            maxlength="11">
            <div v-if="errors && errors.card" class="invalid-feedback">{{ errors.card[0] }}</div>
        </div>
      </div>

      <div class="form-group">
        <label for="card">Email</label>
        <input type="email" class="form-control" v-model="resident.email">
      </div>

      <div class="form-check form-check-inline">
        <input type="checkbox" v-model="resident.disability" class="form-check-input" id="disability" />
        <label for="disability" class="form-check-label">
          Movilidad reducida
        </label>
      </div>

      <div class="form-group">
        <label for="card">Foto</label>
        <div class="d-flex align-items-center">
          <button
            @click="camera = true"
            type="button"
            class="btn btn-primary d-flex me-4"
            >
            <i class="material-symbols-outlined">camera</i>
          </button>

          <input
            ref="fileInput"
            @change="fileInput.files.length ? (picture = fileInput.files[0]) : ''"
            type="file"
            class="form-control"
            >
        </div>
      </div>

      <div class="d-flex justify-content-end">
        <template v-if="!loading">
            <button
            v-if="resident"
            type="button"
            class="btn btn-link me-3"
            @click="$emit('reset')">
            Cancelar
          </button>
          <button
            type="submit"
            class="btn btn-primary justify-content-center">
            {{ resident.id ? 'Actualizar' : 'Agregar' }}
          </button>
        </template>
        <button v-else class="btn btn-primary">Cargando...</button>
      </div>
    </form>
  </div>
</template>
  
<script setup>
import { ref, computed, watch } from 'vue'
import Camera from '../../components/Camera.vue';

const props = defineProps(['resident', 'extension'])
const emit  = defineEmits('reset', 'residentUpdated', 'residentStored')

const storeResidentForm = ref(null)
const picture           = ref(null)
const fileInput         = ref(null)

watch(() => props.resident, (newValue, oldValue) => {
  if( !newValue.id ) picture.value = null
  resident.value = { ...newValue }
})

const loading     = ref(false)
const errors      = ref({})
const camera      = ref(false)
const resident    = ref({})
const typeChecked = computed(() => resident.value.is_resident || resident.value.is_owner || resident.value.is_authorized)

function updatePicture(blob) {
  picture.value = blob
  camera.value = false
}

function loadData() {
  loading.value = true
  errors.value = {}
  let data = new FormData();
  data.append('extension_id', props.extension.id)
  Object.keys(resident.value).map(key => {
    data.append(key, resident.value[key] != null ? resident.value[key] : '')
  })
  if ( resident.value.id) { data.append('_method', 'PUT') }
  if ( picture.value)     { data.append('picture', picture.value) }
  return data;
}

function updateResident() {
  let data = loadData()
  axios.post(`/residents/${resident.value.id}`, data)
  .then(response => {
    emit('residentUpdated', response.data.data)
    fileInput.value.value = ''
    picture.value = ''
  })
  .catch(error => {
    if (error.response.status == '422') {
      errors.value = error.response.data.errors ? error.response.data.errors : {}
    }
  })
  .finally(() => loading.value = false)
}

function storeResident() {
  let data = loadData()
  axios.post('residents', data)
  .then(response => {
    emit('residentStored', response.data.data)
    fileInput.value.value = ''
    picture.value = ''
  })
  .catch(error   => {
    if (error.response.status == '422') {
      errors.value = error.response.data.errors ? error.response.data.errors : {}
    }
  })
  .finally(() => loading.value = false)
}
</script>
  
<style lang="scss" scoped>
.form-check-label {
  color: #0075ff;
}
label {
  font-size: .9rem;
}
.form-group {
  margin-bottom: .5rem;
}
.form-group .form-control {
  border: 1px solid #000;
}
</style>

