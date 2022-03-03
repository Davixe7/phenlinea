<template>
  <div id="create-resident">
    <form ref="residentsForm">
    <div class="form-group">
      <label for="admin_id">Apartamento</label>
      <select name="extension_id" id="extension_id" class="form-control" v-model="extensionId" required>
        <option v-for="extension in extensions" :value="extension.id">{{ extension.id + ' ' + extension.name }}</option>
      </select>
    </div>

    <div class="form-row">
      <div class="col-md-8">
        <div class="form-group">
          <label for="name">Nombre</label>
          <input type="text" class="form-control" :class="{'is-invalid':errors.name}" id="name" v-model="name" required>
          <div v-if="errors.name" class="invalid-feedback">{{errors.name[0]}}</div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="form-group">
          <label for="dni">DNI</label>
          <input type="text" class="form-control" :class="{'is-invalid':errors.dni}" id="dni" v-model="dni" required>
          <div v-if="errors.dni" class="invalid-feedback">{{errors.dni[0]}}</div>
        </div>
      </div>
    </div>


    <div class="form-group">
      <label for="age">Edad</label>
      <input type="number" class="form-control" :class="{'is-invalid':errors.age}" id="age" v-model="age" required>
      <div v-if="errors.age" class="invalid-feedback">{{errors.age[0]}}</div>
    </div>
    
    <div class="form-group">
      <label for="disability">Posee discapacidad</label>
      <input type="check" class="form-control" :class="{'is-invalid':errors.disability}" id="disability" v-model="disability" required>
      <div v-if="errors.disability" class="invalid-feedback">{{errors.disability[0]}}</div>
    </div>

    <div class="form-row">
      <label class="col-md-6">Tipo</label>
      <div class="col-md-6 text-right">
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="checkbox" id="isOwner" v-model="isOwner">
          <label class="form-check-label" for="isOwner">Propietario</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="checkbox" id="isResident" v-model="isResident">
          <label class="form-check-label" for="isResident">Residente</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="checkbox" id="isAuthorized" v-model="isAuthorized">
          <label class="form-check-label" for="isAuthorized">Autorizado</label>
        </div>
      </div>
    </div>

    <div class="form-group text-right">
      <button type="button" v-if="!editing" class="btn btn-primary" @click="storeResident">Enviar</button>
      <button type="button" v-else class="btn btn-primary" @click="updateResident">Actualizar</button>
    </div>
    </form>
  </div>
</template>

<script>
export default {
  props: {
    'residentToEdit': Object,
    'editing': Boolean,
  },
  data(){
    return {
      'extensions': [],
      'errors': [],
      'extensionId': null,
      'residentId': null,
      'name': '',
      'age': '',
      'dni': '',
      'isOwner': false,
      'isResident': false,
      'isAuthorized': false,
      'disability': false
    }
  },
  watch:{
    residentToEdit(newResident, oldResident){
      if( newResident ){
        this.name          = newResident.name
        this.age           = newResident.age
        this.dni           = newResident.dni
        this.isOwner       = newResident.is_owner
        this.isResident    = newResident.is_resident
        this.isAuthorized    = newResident.isAuthorized
        this.extension_id  = newResident.extension_id
        this.residentId    = newResident.id
        this.disability    = newResident.disability
      }else {
        this.clearForm()
      }
    }
  },
  methods:{
    storeResident(){
      if( !this.$refs.residentsForm.reportValidity() ){ return }
      let data = this.loadData();
      axios.post('/residents', data).then((response)=>{
        this.$emit('residentStored', response.data.data)
        this.clearForm()
      })
      .catch((error)=>{
        this.errors = error.response.data.errors
      })
    },
    updateResident(){
      let data = this.loadData();
      data.append('_method', 'PUT');

      axios.post('/residents/'+this.residentId, data).then((response)=>{
        this.$emit('residentUpdated', response.data.data)
        this.clearForm()
      },error => {
        if( error.status == 403 ){
          this.$toasted.error('No tienes permisos para realizar esta acción', {'position':'bottom-left'})
          return
        }
        this.errors = error.response.data.errors
      })
    },
    loadData(){
      let data = new FormData();
      data.append('name', this.name);
      data.append('age', this.age);
      data.append('dni', this.dni);
      data.append('is_owner', (this.isOwner) ? 1 : 0);
      data.append('is_resident', this.isResident ? 1 : 0);
      data.append('is_authorized', this.isAuthorized ? 1 : 0);
      data.append('extension_id', this.extensionId);
      data.append('disability', this.disability ? 1 : 0);
      return data;
    },
    clearForm(){
      this.name = ''
      this.age = ''
      this.dni = ''
      this.is_owner = ''
      this.is_authorized = ''
      this.is_resident = ''
      this.disability = false
      this.errors = []
    }
  },
  mounted(){
    axios.get('/extensions/list').then((response)=>{
      this.extensions = response.data.data
      this.extensionId = this.extensions[0].id
    })
    .catch((error)=>{
      this.errors = error.response.data.errors
    })
  }
}
</script>
