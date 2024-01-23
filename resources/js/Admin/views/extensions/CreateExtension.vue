<template>
  <div id="create-census">
    <extensions-nav :extension="extension" :page="'extension'"/>

    <form ref="storeCensusForm" id="create-census-form" @submit.prevent="extension.id ? updateCensus : storeCensus">
      <div class="row" style="margin-bottom: 50px;">
        <div class="col-md-4 mb-3">
          <div class="form-section">
            <h4>
              <i class="material-symbols-outlined">meeting_room</i>
              Información del apartamento
            </h4>
            <div class="form-group row">
              <label class="col-7" title="requerido">
                <i class="material-symbols-outlined">meeting_room</i> Número Apto. <span class="text-red">*</span>
              </label>
              <div class="col-5">
                <input type="tel" class="form-control" :class="{ 'is-invalid': errors.name }" v-model="extension.name"
                  placeholder="número de apartamento" required>
                <div v-if="errors.name" class="invalid-feedback">{{ errors.name[0] }}</div>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-7" title="requerido">
                <i class="material-symbols-outlined">account_circle</i> Nombre propietario
              </label>
              <div class="col-5">
                <input type="text" class="form-control" :class="{ 'is-invalid': errors.owner_name }"
                  v-model="extension.owner_name">
                <div v-if="errors.owner_name" class="invalid-feedback">{{ errors.owner_name[0] }}</div>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-7" title="requerido">
                <i class="material-symbols-outlined">phone</i> Teléfono propietario
              </label>
              <div class="col-5">
                <input type="tel" class="form-control" :class="{ 'is-invalid': errors.owner_phone }" maxlength="10"
                  minlength="10" v-model="extension.owner_phone">
                <div v-if="errors.owner_phone" class="invalid-feedback">{{ errors.owner_phone[0] }}</div>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-7">
                <i class="material-symbols-outlined">alternate_email</i> Email
              </label>
              <div class="col-5">
                <input type="email" class="form-control" v-model="extension.email">
              </div>
            </div>

            <div class="form-group row">
              <label class="col-7">
                <i class="material-symbols-outlined">pets</i> Número de Mascotas
              </label>
              <div class="col-5">
                <input type="number" class="form-control" v-model="extension.pets_count">
              </div>
            </div>

            <div class="form-group row">
              <label class="col-7">
                <i class="material-symbols-outlined">format_paint</i> Parqueadero
              </label>
              <div class="col-5">
                <select v-model="extension.has_own_parking" id="" class="form-control">
                  <option :value="0">Arrendado</option>
                  <option :value="1">Propio</option>
                </select>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-7">
                <i class="material-symbols-outlined">filter_1</i>
                Número Parqueadero
              </label>
              <div class="col-5">
                <input type="tel" class="form-control" v-model="extension.parking_number1">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-7">
                <i class="material-symbols-outlined">filter_2</i>
                Número Parqueadero
              </label>
              <div class="col-5">
                <input type="tel" class="form-control" v-model="extension.parking_number2">
              </div>
            </div>

            <div class="form-group row">
              <label class="col-7">
                <i class="material-symbols-outlined">format_paint</i>
                Cuarto útil
              </label>
              <div class="col-5">
                <input type="text" class="form-control" v-model="extension.deposit" />
              </div>
            </div>

            <label>
              <i class="material-symbols-outlined">phone</i>
              Contácto Emergencia
            </label>
            <div class="row my-3">
              <div class="form-group col-6">
                <input type="text" class="form-control" :class="{ 'is-invalid': errors.emergency_contact_name }"
                  placeholder="Nombre" v-model="extension.emergency_contact_name">
                <div v-if="errors.emergency_contact_name" class="invalid-feedback">{{
                  errors.emergency_contact_name[0]
                }}</div>
              </div>
              <div class="form-group col-6">
                <input type="tel" class="form-control" :class="{ 'is-invalid': errors.emergency_contact }"
                  placeholder="Teléfono" maxlength="10" minlength="10" v-model="extension.emergency_contact">
                <div v-if="errors.emergency_contact" class="invalid-feedback">
                  {{ errors.emergency_contact[0] }}
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-4 mb-3">
          <div class="form-section">
            <h4>
              <i class="material-symbols-outlined">edit</i>
              Observaciones
            </h4>
            <div class="form-group">
              <label for="observaciones" class="d-none">
                Observaciones
              </label>
              <textarea class="form-control" rows="3" v-model="extension.observation"></textarea>
            </div>
          </div>
        </div>

        <div class="col-md-4 mb-3">
          <div class="form-section color-card mb-3">
            <h4>
              <i class="material-symbols-outlined">contactless</i>
              Citofonía
            </h4>
            <div class="form-group">
              <label for="line1" title="requerido">
                Línea 1
                <img :src="`/img/icons8-whatsapp.svg`" style="width: 20px; height: 20px; margin-left: auto;">
              </label>
              <div>
                <input type="tel" class="form-control" :class="{ 'is-invalid': errors.phone_1 }" maxlength="10"
                  minlength="10" v-model="extension.phone_1" style="width: 248px;">
                <div v-if="errors.phone_1" class="invalid-feedback">{{ errors.phone_1[0] }}</div>

                <select
                  style="width: 248px;"
                  v-if="extension.residents && extension.residents.length"
                  class="form-control"
                  v-model="extension.resident_id">
                  <option value="">Seleccione títular</option>
                  <option
                    v-for="resident in extension.residents"
                    :key="resident.id"
                    :value="resident.id">
                    {{  resident.name  }}
                  </option>
                </select>
                <span v-else class="help-text">Registre residentes para asociar al número telefónico</span>
              </div>
            </div>

            <div class="form-group">
              <label for="line1">
                <span>Línea 2</span>
                <img :src="`/img/icons8-whatsapp.svg`" style="width: 20px; height: 20px; margin-left: auto;">
              </label>

              <div>
                <input
                  type="tel"
                  class="form-control"
                  :class="{ 'is-invalid': errors.phone_2 }" maxlength="10"
                  minlength="10"
                  v-model="extension.phone_2"
                  style="width: 248px;">
                <div v-if="errors.phone_2" class="invalid-feedback">{{ errors.phone_2[0] }}</div>
                <select
                    style="width: 248px;"
                    v-if="extension.residents && extension.residents.length"
                    class="form-control"
                    v-model="extension.resident_id_2">
                    <option value="">Seleccione títular</option>
                    <option
                      v-for="resident in extension.residents"
                      :key="resident.id"
                      :value="resident.id">
                      {{  resident.name  }}
                    </option>
                </select>
                <span v-else class="help-text">Registre residentes para asociar al número telefónico</span>
              </div>
            </div>

            <div class="form-group">
              <label for="line3">
                <span>Línea 3</span>
              </label>

              <div>
                <input
                  type="tel"
                  class="form-control"
                  :class="{ 'is-invalid': errors.phone_3 }" maxlength="10"
                  minlength="10"
                  v-model="extension.phone_3"
                  style="width: 248px;">
                <div v-if="errors.phone_3" class="invalid-feedback">{{ errors.phone_3[0] }}</div>
                <select
                    style="width: 248px;"
                    v-if="extension.residents && extension.residents.length"
                    class="form-control"
                    v-model="extension.resident_id_3">
                    <option value="">Seleccione títular</option>
                    <option
                      v-for="resident in extension.residents"
                      :key="resident.id"
                      :value="resident.id">
                      {{  resident.name  }}
                    </option>
                </select>
                <span v-else class="help-text">Registre residentes para asociar al número telefónico</span>
              </div>
            </div>

            <div class="form-group">
              <label for="line4">Línea 4</label>

              <div>
                <input
                  type="tel"
                  class="form-control"
                  :class="{ 'is-invalid': errors.phone_4 }" maxlength="10"
                  minlength="10"
                  v-model="extension.phone_4"
                  style="width: 248px;">
                <div v-if="errors.phone_4" class="invalid-feedback">{{ errors.phone_4[0] }}</div>
                <select
                    style="width: 248px;"
                    v-if="extension.residents && extension.residents.length"
                    class="form-control"
                    v-model="extension.resident_id_4">
                    <option value="">Seleccione títular</option>
                    <option
                      v-for="resident in extension.residents"
                      :key="resident.id"
                      :value="resident.id">
                      {{  resident.name  }}
                    </option>
                </select>
                <span v-else class="help-text">Registre residentes para asociar al número telefónico</span>
              </div>
            </div>
          </div>

          <div class="d-flex justify-content-end">
            <a href="/extensions" class="btn btn-link btn-link-primary">Terminar</a>
            <button class="btn btn-secondary w-100 justify-content-center" dark v-if="!extension.id"
              @click="storeCensus()" :loading="loading">
              Registrar Extensión
            </button>
            <a v-if="extension.defineComponent" :href="`/extensions`" class="btn btn-link">
              Terminar
            </a>
            <button dark v-if="extension.id" class="btn btn-primary justify-content-center text-center"
              @click="updateCensus()" :loading="loading">
              Actualizars
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>
    
<script setup>
import Vue from 'vue'
import { onMounted, ref } from 'vue'
import ExtensionsNav from './ExtensionsNav.vue'
import "vue-toastification/dist/index.css"
import { createToastInterface } from 'vue-toastification'

var toast = null
const props = defineProps(['_extension'])
const storeCensusForm = ref(null)
const loading = ref(false)
const errors = ref({})
const extension = ref({
  name: '',
  phone_1: null,
  phone_2: null,
  phone_3: null,
  phone_4: null,
  email: '',
  owner_phone: '',
  owner_name: '',
  emergency_contact: null,
  emergency_contact_name: null,
  petsCount: 0,
  has_own_parking: false,
  parking_number1: null,
  parking_number2: null,
  deposit: '',
  password: '',
  observation: '',
  resident_id: null,
  resident_id_2: null,
  resident_id_3: null,
  resident_id_4: null,
})

function storeCensus() {
  if (!storeCensusForm.value.reportValidity()) return
  axios.post('/extensions', { ...extension.value })
    .then(response => {
      extension.value = response.data.data
      toast.success('Creado con éxito!')
      window.location.href = `/extensions/${extension.value.id}/edit`
    })
    .catch(error => {
      errors.value = error.response.data.errors
    })
    .finally(r => loading.value = false)
}

function updateCensus() {
  if (!storeCensusForm.value.reportValidity()) return
  let data = { ...extension.value }
  data._method = 'PUT'
  axios.post(`/extensions/${extension.value.id}`, data)
    .then(response => {
      extension.value = response.data.data
      toast.success('Actualizado con éxito!')
    })
    .catch(error => console.log(error.response.data))
}

onMounted(() => {
  toast = createToastInterface({ eventBus: new Vue() })
  if (props._extension) {
    extension.value = { ...props._extension }
  }
})
</script>
    
<style lang="scss">
.form-check {
  padding-left: 0;

  label {
    input {
      margin-top: 0;
      margin-right: .5em;
    }
  }

  .form-check-input {
    margin-left: 0;
    margin-right: .5rem;
  }
}

ul.vehicle-list {
  padding: 0 !important;
  margin-bottom: 15px;
  display: flex;
  flex-flow: row wrap;
  width: 100%;

  li {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex: 1 0 100%;
    padding: 5px 15px !important;
    border-radius: 5px;
    background: #efefef;

    input {
      max-width: 120px;
      margin-left: 10px;
      padding: 5px 10px;
      border-radius: 5px;
      background: #fff;
      margin-left: auto;
    }

    span {
      cursor: pointer;
      font-weight: 600;
    }
  }
}

.form-section {
  padding: 15px;
  border: 1px solid #dee2e6;
  border-radius: 7px;
}

.form-section>h4 {
  color: #0a47e4 !important;
  margin: 0 0 24px;
  font-size: 1.2em;
}

.color-card {
  color: #fff;
  background: linear-gradient(45deg, #3c00f5, rgb(66, 72, 150)) !important;

  .form-group {
    display: flex;
    margin-bottom: 20px;
  }

  h4 {
    color: #fff !important;
  }

  label {
    flex: 1 0 74px;
  }
}

.dropdown-toggler i.material-symbols-outlined {
  font-size: 1.5em;
  margin-left: 5px;
  color: #9f9f9f;
}

.text-red {
  color: red !important;
}

h4 {
  color: #fff;
  padding: 0;
  background: none;
  box-shadow: none;
}

.plates-input {
  text-transform: uppercase;
}
</style>

