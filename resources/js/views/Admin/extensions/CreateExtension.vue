<template>
  <div id="create-census">
    <form ref="storeCensusForm" id="create-census-form" @submit.prevent="">
      <div class="row d-flex align-items-center mb-4">
        <h1 class="col-md-6 mb-0" style="color: #000;">
          Censo apartamento
        </h1>
        <div v-if="extension && extension.id" class="col-md-6 text-right d-flex align-items-center justify-content-end">
          <b>KEY {{ password }}</b>
          <div class="options-dropdown">
            <div class="dropdown">
              <span class="dropdown-toggler" data-toggle="dropdown">
                <i class="material-symbols-outlined">more_vert</i>
              </span>
              <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="#" @click.prevent="resetPassword()">Restablecer contraseña</a>
              </div>
            </div>
          </div>
        </div>
      </div>

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
                <input type="tel" class="form-control" :class="{ 'is-invalid': errors.name }" v-model="extName"
                  placeholder="número de apartamento" required>
                <div v-if="errors.name" class="invalid-feedback">{{ errors.name[0] }}</div>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-7" title="requerido">
                <i class="material-symbols-outlined">account_circle</i> Nombre propietario
              </label>
              <div class="col-5">
                <input type="text" class="form-control" :class="{ 'is-invalid': errors.owner_name }" v-model="owner_name">
                <div v-if="errors.owner_name" class="invalid-feedback">{{ errors.owner_name[0] }}</div>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-7" title="requerido">
                <i class="material-symbols-outlined">phone</i> Teléfono propietario
              </label>
              <div class="col-5">
                <input type="tel" class="form-control" :class="{ 'is-invalid': errors.owner_phone }" maxlength="10"
                  minlength="10" v-model="ownerPhone">
                <div v-if="errors.owner_phone" class="invalid-feedback">{{ errors.owner_phone[0] }}</div>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-7">
                <i class="material-symbols-outlined">alternate_email</i> Email
              </label>
              <div class="col-5">
                <input type="email" class="form-control" v-model="email">
              </div>
            </div>

            <div class="form-group row">
              <label class="col-7">
                <i class="material-symbols-outlined">pets</i> Número de Mascotas
              </label>
              <div class="col-5">
                <input type="number" class="form-control" v-model="petsCount">
              </div>
            </div>

            <div class="form-group row">
              <label class="col-7">
                <i class="material-symbols-outlined">format_paint</i> Parqueadero
              </label>
              <div class="col-5 form-check form-check-inline me-0">
                <label for="has_own_parking_true" class="form-check-label me-2">
                  <input type="radio" v-model="has_own_parking" :value="1" class="form-check-input ms-0"
                    id="has_own_parking_true" />
                  Propio
                </label>
                <label for="has_own_parking_false" class="form-check-label">
                  <input type="radio" v-model="has_own_parking" :value="0" class="form-check-input ms-0"
                    id="has_own_parking_false" />
                  Arrendado
                </label>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-7">
                <i class="material-symbols-outlined">filter_1</i>
                Número Parqueadero
              </label>
              <div class="col-5">
                <input type="tel" class="form-control" v-model="parkingNumber1">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-7">
                <i class="material-symbols-outlined">filter_2</i>
                Número Parqueadero
              </label>
              <div class="col-5">
                <input type="tel" class="form-control" v-model="parkingNumber2">
              </div>
            </div>

            <div class="form-group row">
              <label class="col-7">
                <i class="material-symbols-outlined">format_paint</i>
                Cuarto útil
              </label>
              <div class="col-5">
                <input type="text" class="form-control" v-model="hasDeposit"/>
              </div>
            </div>

            <label>
              <i class="material-symbols-outlined">phone</i>
              Contácto Emergencia
            </label>
            <div class="row my-3">
              <div class="form-group col-6">
                <input type="text" class="form-control" :class="{ 'is-invalid': errors.emergency_contact_name }"
                  placeholder="Nombre" v-model="emergency_contact_name">
                <div v-if="errors.emergency_contact_name" class="invalid-feedback">{{
                  errors.emergency_contact_name[0]
                }}</div>
              </div>
              <div class="form-group col-6">
                <input type="tel" class="form-control" :class="{ 'is-invalid': errors.emergency_contact }"
                  placeholder="Teléfono" maxlength="10" minlength="10" v-model="emergency_contact">
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
              <i class="material-symbols-outlined">
                directions_car
              </i>
              Vehículos
            </h4>
            <div class="d-flex mb-3">
              <input type="text" class="form-control" placeholder="Placa" v-model="newVehicle.plate"
                style="max-width: 150px;">
              <select class="form-control" v-model="newVehicle.type">
                <option value="bike">Moto</option>
                <option value="car">Carros</option>
              </select>
              <button type="button" @click="addVehicle" class="btn btn-light">
                agregar
              </button>
            </div>

            <div v-show="cars && cars.length">
              <label class="mt-2">
                Carros
              </label>
              <ul class="vehicle-list">
                <li v-for="car in cars">
                  <div>{{ car.plate }}</div>
                  <input type="text" v-model="car.tag" placeholder="TAG">
                  <span @click="removeVehicle(car.plate)">&times;</span>
                </li>
              </ul>
            </div>

            <div v-show="bikes && bikes.length">
              <label>
                Motos
              </label>
              <ul class="vehicle-list">
                <li v-for="bike in bikes">
                  <div>{{ bike.plate }}</div>
                  <input type="text" v-model="bike.tag" placeholder="TAG">
                  <span @click="removeVehicle(bike.plate)">&times;</span>
                </li>
              </ul>
            </div>

            <h4>
              <i class="material-symbols-outlined">edit</i>
              Observaciones
            </h4>
            <div class="form-group">
              <label for="observaciones" class="d-none">
                Observaciones
              </label>
              <textarea class="form-control" rows="3" v-model="observation"></textarea>
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
                Línea 1 <span class="text-red">*</span>
                <img src="/img/icons8-whatsapp.svg" style="width: 20px; height: 20px; margin-left: auto;">
              </label>
              <input type="tel" class="form-control" :class="{ 'is-invalid': errors.phone_1 }" maxlength="10"
                minlength="10" v-model="phone_1" required>
              <div v-if="errors.phone_1" class="invalid-feedback">{{ errors.phone_1[0] }}</div>
            </div>
            <div class="form-group">
              <label for="line1">
                <span>
                  Línea 2
                </span>
                <img src="/img/icons8-whatsapp.svg" style="width: 20px; height: 20px; margin-left: auto;">
              </label>
              <input type="tel" class="form-control" :class="{ 'is-invalid': errors.phone_2 }" maxlength="10"
                minlength="10" v-model="phone_2">
              <div v-if="errors.phone_2" class="invalid-feedback">{{ errors.phone_2[0] }}</div>
            </div>
            <div class="form-group">
              <label for="line1">Línea 3</label>
              <input type="tel" class="form-control" :class="{ 'is-invalid': errors.phone_3 }" maxlength="10"
                minlength="10" v-model="phone_3">
              <div v-if="errors.phone_3" class="invalid-feedback">{{ errors.phone_3[0] }}</div>
            </div>
            <div class="form-group">
              <label for="line1">Línea 4</label>
              <input type="tel" class="form-control" :class="{ 'is-invalid': errors.phone_4 }" maxlength="15" minlength="3"
                v-model="phone_4">
              <div v-if="errors.phone_4" class="invalid-feedback">{{ errors.phone_4[0] }}</div>
            </div>
          </div>

          <div class="d-flex justify-content-end">
            <button class="btn btn-secondary w-100 justify-content-center" dark v-if="!editing" @click="storeCensus()"
              :loading="loading">
              Registrar Extensión
            </button>
            <a v-if="editing" href="/extensions" class="btn btn-link">
              Terminar
            </a>
            <button dark v-if="editing" class="btn btn-secondary justify-content-center text-center"
              @click="updateCensus()" :loading="loading">
              Actualizar
            </button>
          </div>
        </div>
      </div>

      <div v-if="!extensionId" class="alert alert-info">
        <i class="material-symbols-outlined">
          error_outline
        </i>
        Registre la extensión para asociar residentes
      </div>
      <Residents v-else :_residents="residents" :extensionId="extensionId" />
    </form>
  </div>
</template>
    
<script>
import Residents from '../residents/Residents.vue'
export default {
  components: { Residents },
  name: 'CreateCensus',
  props: ['extensionId'],
  data() {
    return {
      extension: {},
      residents: [],
      vehicles: [],
      newVehicle: { type: 'car' },

      extName: '',
      phone_1: null,
      phone_2: null,
      phone_3: null,
      phone_4: null,

      email: '',
      ownerPhone: '',
      owner_name: '',
      emergency_contact: null,
      emergency_contact_name: null,
      petsCount: 0,
      has_own_parking: false,
      parkingNumber1: null,
      parkingNumber2: null,
      hasDeposit: '',
      password: '',
      observation: '',

      errors: [],
      loading: false
    }
  },
  watch: {
    extension(newVal) {
      this.password = newVal._password
      this.extName = newVal.name
      this.phone_1 = newVal.phone_1
      this.phone_2 = newVal.phone_2
      this.phone_3 = newVal.phone_3
      this.phone_4 = newVal.phone_4

      this.email = newVal.email
      this.ownerPhone = newVal.owner_phone
      this.owner_name = newVal.owner_name
      this.emergency_contact = newVal.emergency_contact
      this.emergency_contact_name = newVal.emergency_contact_name
      this.petsCount = newVal.pets_count

      this.has_own_parking = newVal.has_own_parking
      this.parkingNumber1 = newVal.parking_number1
      this.parkingNumber2 = newVal.parking_number2
      this.hasDeposit     = newVal.deposit

      this.vehicles = (newVal.vehicles) ? newVal.vehicles : []
      this.observation = newVal.observation
    }
  },
  computed: {
    editing() {
      return this.extensionId || this.extension.id
    },
    parkingNumbers() {
      if (this.parkingNumber1 && this.parkingNumber2) {
        return this.parkingNumber1 + "," + this.parkingNumber2
      }
      if (this.parkingNumber1) {
        return this.parkingNumber1
      }
      return this.parkingNumber2
    },
    bikes() {
      return this.vehicles.filter(v => v.type == 'bike')
    },
    cars() {
      return this.vehicles.filter(v => v.type == 'car')
    },
  },
  methods: {
    addVehicle() {
      if (this.newVehicle.plate) {
        this.newVehicle.plate = this.newVehicle.plate.toUpperCase().trim()
        this.vehicles.push({ ...this.newVehicle })
        this.newVehicle = { type: 'car' }
      }
    },
    removeVehicle(v) {
      if (!window.confirm('¿Seguro que desea eliminar el vehículo?')) { return }
      this.vehicles = this.vehicles.filter(cV => v != cV.plate)
    },
    storeCensus() {
      if (this.$refs.storeCensusForm.reportValidity()) {
        this.loading = true
        let data = this.loadData()
        axios.post('/extensions', data).then(response => {
          this.extension = response.data.data
          this.$toasted.success('Datos guardados exitosamente')
          window.location.href = `/extensions/${this.extension.id}/edit`
        }, error => {
          this.errors = error.response.data.errors
          this.$toasted.error('Error al guardar el censo')
        }).then(r => {
          this.loading = false
        })
      }
    },
    updateCensus() {
      if (this.$refs.storeCensusForm.reportValidity()) {
        let data = this.loadData()
        data._method = 'PUT'
        axios.post('/extensions/' + this.extensionId, data).then(response => {
          this.extension = response.data.data
          this.$toasted.success('Datos guardados exitosamente')
        }, error => {
          console.log(error.response.data)
          this.$toasted.error('Error al guardar el censo')
        })
      }
    },
    resetPassword() {
      if (confirm('¿Seguro que desea restablecer la contraseña?')) {
        axios.post(`/extensions/${this.extension.id}/resetpassword`, { '_method': 'PUT' })
          .then(response => {
            this.password = response.data.data._password
          }, error => {
            console.log(error);
          })
      }
    },
    loadData() {
      let data = {
        name: this.extName,
        phone_1: this.phone_1,
        phone_2: this.phone_2,
        phone_3: this.phone_3,
        phone_4: this.phone_4,

        email: this.email,
        owner_phone: this.ownerPhone,
        owner_name: this.owner_name,
        emergency_contact: this.emergency_contact,
        emergency_contact_name: this.emergency_contact_name,

        has_own_parking: this.has_own_parking,
        parking_number1: this.parkingNumber1,
        parking_number2: this.parkingNumber2,

        vehicles: this.vehicles,
        observation: this.observation,
        pets_count: this.petsCount,
        deposit: this.deposit,
      }
      return data
    },
    fetchCensus() {
      axios.get('/extensions/' + this.extensionId).then(response => {
        this.residents = response.data.data.residents
        this.extension = response.data.data
      }, error => {
        console.log(error.response.data)
      })
    }
  },
  mounted() {
    if (this.extensionId) {
      this.fetchCensus()
    }
  }
}
</script>
    
<style lang="scss">
.form-check {
  padding-left: 0;
  label {
    input { margin-top: 0; margin-right: .5em; }
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

  .vehicle-list li {
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

