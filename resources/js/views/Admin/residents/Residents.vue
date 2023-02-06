<template>
  <div class="row">
    
    <div class="col-md-4">
      <div class="form-section">
        <h4>
          <i class="material-icons">account_circle</i>
          Información personal
        </h4>
        <form action="" id="create-resident-form" ref="storeResidentForm" @submit.prevent="storeResident">
          <div class="form-group">
            <label for="type" class="d-block">
              Tipo de persona
            </label>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox1" v-model="is_owner" name="tipo de residente">
              <label class="form-check-label" for="inlineCheckbox1">Propietario</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox2" v-model="is_resident" name="tipo de residente">
              <label class="form-check-label" for="inlineCheckbox2">Residente</label>
            </div>
            <div class="form-check form-check-inline me-0">
              <input class="form-check-input" type="checkbox" id="is_authorized" v-model="is_authorized">
              <label class="form-check-label" for="is_authorized">Autorizado</label>
            </div>
            <span class="invalid-feedback text-right" :class="{'d-block':!typeChecked}">
              Por favor seleccione el tipo de residente
            </span>
          </div>
          
          <div class="row">
            <div class="form-group col-md-9">
              <label for="name">Nombres</label>
              <input type="text" class="form-control" v-model="name" maxlength="120" required>
            </div>
            <div class="form-group col-md-3">
              <label for="age">Edad</label>
              <input type="number" class="form-control" v-model="age" min="1" max="120" required>
            </div>
          </div>
          
          <div class="form-group">
            <label for="dni">Cédula</label>
            <input type="tel" class="form-control" v-model="dni" minlength="5" maxlength="11">
          </div>
          
          <div v-if="resident && resident.id" class="form-group">
            <label for="card">Tarjeta</label>
            <input type="tel" class="form-control" v-model="card" minlength="5" maxlength="11">
          </div>
          
          <div class="form-check form-check-inline">
            <input
            type="checkbox"
            v-model="disability"
            class="form-check-input"
            id="disability"
            />
            <label for="disability" class="form-check-label">
              Posee discapacidad
            </label>
          </div>
          
          <div class="text-right">
            <button v-if="resident" type="button" class="btn btn-link" @click="clearForm">
              Cancelar
            </button>
            <button type="submit" class="btn btn-light">
              {{ resident && resident.id ? 'actualizar' : 'agregar' }}
            </button>
          </div>
        </form>
      </div>
    </div>
    
    <div class="col-md-8">
      <div class="form-section mb-0" style="padding-bottom: 7px;">
        <h4>
          <i class="material-icons">supervisor_account</i> Núcleo Familiar
        </h4>
        <div v-if="residents && residents.length" class="table-wrap">
          <table class="table">
            <thead>
              <th>Nombre</th>
              <th>Edad</th>
              <th>Documento</th>
              <th>Tarjeta</th>
              <th>P</th>
              <th>R</th>
              <th>A</th>
              <th>D</th>
              <th class="text-right">Opciones</th>
            </thead>
            <tbody>
              <tr v-for="resident in residents">
                <td>{{ resident.name }}</td>
                <td>{{ resident.age }}</td>
                <td>{{ resident.dni }}</td>
                <td>{{ resident.card }}</td>
                <td>
                  <i v-if="resident.is_owner" class="material-icons">done</i>
                </td>
                <td>
                  <i v-if="resident.is_resident" class="material-icons">done</i>
                </td>
                <td>
                  <i v-if="resident.is_authorized" class="material-icons">done</i>
                </td>
                <td>
                  <i v-if="resident.disability" class="material-icons">done</i>
                </td>
                <td>
                  <button type="button" class="btn btn-link" @click="editResident(resident)">
                    <i class="material-icons">edit</i>
                  </button>
                  <a href="#" @click="removeResident(resident.id)">
                    <i class="material-icons">close</i>
                  </a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div v-else class="alert alert-info">
          <i class="material-icons">error_outline</i>
          No hay registros de residente para mostrar
        </div>
      </div>
    </div>      
  </div>
</template>
  
  <script>
  export default {
    name: 'Residents',
    props: {
      _residents: {type: Array, default: []},
      extensionId: {type: Number, default: null},
    },
    data(){
      return {
        name: null,
        age: null,
        dni: '',
        is_resident: true,
        is_owner: false,
        is_authorized: false,
        disability: false,
        residents: [],
        resident: null,
        card: ''
      }
    },
    watch:{
      _residents(){
        this.residents = this._residents
      }
    },
    computed:{
      typeChecked(){
        return this.is_resident || this.is_owner || this.is_authorized
      }
    },
    methods:{
      editResident(resident){
        this.resident = resident
        let atts = ['name', 'age', 'dni', 'is_resident', 'is_owner', 'is_authorized', 'disability', 'card']
        atts.forEach(attr => this[ attr ] = this.resident[attr])
      },
      storeResident(){
        if( this.$refs.storeResidentForm.reportValidity() && this.typeChecked){
          let data = {
            name: this.name,
            age: this.age,
            dni: this.dni,
            is_owner: this.is_owner,
            is_resident: this.is_resident,
            is_authorized: this.is_authorized,
            extension_id: this.extensionId,
            disability: this.disability,
            card: this.card
          }
          let url = '/residents'
          
          if(this.resident && this.resident.id){
            url = '/residents/' + this.resident.id
            data['_method'] = 'PUT'
          }
          
          axios.post(url, data).then(response=>{
            if( this.resident && this.resident.id ){
              console.log("replacing");
              this.residents.splice( this.residents.indexOf(this.resident), 1, response.data.data )
            }else {
              this.residents.push(response.data.data)
            }
            this.$toasted.success('Residente registrado con éxito')
            this.clearForm()
          },error=>{
            console.log( error.response );
            this.$toasted.error('Error al registrar al residente')
          })
        }
      },
      removeResident(r){
        if( window.confirm( '¿Seguro que desea eliminar al residente?' ) ){
          let data = {
            _method: 'delete'
          }
          axios.post('/residents/'+r, data).then(response=>{
            this.$toasted.success('Residente eliminado con éxito')
            this.residents = this.residents.filter(cR => r != cR.id)
          },error=>{
            this.$toasted.error('Error al eliminar al residente')
            console.log( error.response.data )
          })
        }
      },
      clearForm(){
        this.resident = null
        this.name = ''
        this.age = ''
        this.dni = ''
        this.is_owner = false
        this.is_resident = false
        this.is_authorized = false
        this.disability = false
        this.card = ''
      }
    },
    mounted(){},
  }
  </script>
  
  <style lang="scss" scoped>
  .form-check-label {
    color: #0075ff;
  }
  </style>

