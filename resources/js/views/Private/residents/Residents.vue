<template>
  <div id="residents">
    <div class="row">
      <div class="col-md-8">
        <div class="page-title">
          <h1>Residentes</h1>
          <div v-if="residents && residents.length" class="ml-auto">
            <form autocomplete="off" onsubmit="e.preventDefault()">
              <input placeholder="buscar por nombre..." autocomplete="none" type="search" class="form-control md-control" v-model="searchText" @keyup="debouncedFilterResidents" name="new-password" id="new-password">
            </form>
          </div>
        </div>
      </div>
    </div>
    
    <div class="row">
      <div class="table-responsive col-md-8">
        <table class="table" v-if="residents.length && results.length">
          <thead>
            <th>Nombre</th>
            <th>Edad</th>
            <th>Documento</th>
            <th>Propietario</th>
            <th>Residente</th>
            <th class="text-right">Opciones</th>
          </thead>
          <tbody>
            <tr v-for="resident in results">
              <td>{{ resident.name }}</td>
              <td>{{ resident.age }}</td>
              <td>{{ resident.dni }}</td>
              <td>{{ resident.is_owner    ? 'SÍ' : 'NO' }}</td>
              <td>{{ resident.is_resident ? 'SÍ' : 'NO' }}</td>
              <td class="text-right">
                <div class="btn-group">
                  <a href="#" class="btn btn-sm btn-secondary" @click="editResident(resident)">
                    <i class="material-icons">edit</i></a>
                  <a href="#" class="btn btn-sm btn-secondary" @click="deleteResident(resident.id)">
                    <i class="material-icons">delete</i></a>
                </div> 
              </td>
            </tr>
          </tbody>
        </table>
        <div v-else class="alert alert-info">No hay residentes para mostrar</div>
      </div>
    </div>

    <div class="fab-container">
      <button type="button" class="btn btn-primary btn-circle" data-toggle="modal" data-target="#exampleModal" @click="editing=false; extToEdit = null">
        <i class="material-icons">add</i>
      </button>
    </div>

    <!-- Modal -->
    <div ref="ResidentsModal" class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
              <span v-if="!editing">Crear</span>
              <span v-else>Actualizar</span>
              residente</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <create-resident
              :residentToEdit="residentToEdit"
              :editing="editing"
              @residentStored="appendResident"
              @residentUpdated="updateResident">
            </create-resident>

          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import CreateResident from './CreateResident.vue'
export default {
  name: 'Residents',
  components: { CreateResident },
  data(){
    return {
      residentToEdit: null,
      editing: false,
      residents: [],
      searchText: '',
      results: []
    }
  },
  watch:{
    residents(){
      this.results = _.cloneDeep( this.residents )
    }
  },
  methods:{
    filterResidents(){
      if( !this.searchText ){
        this.results = this.residents
        return
      }
      this.results = this.residents.filter( p => p.name.toLowerCase().includes(this.searchText.toLowerCase()))
    },
    editResident(resident){
      this.editing = true
      this.residentToEdit = resident
      $( this.$refs.ResidentsModal ).modal('show');
    },
    updateResident(resident){
      this.residents = this.residents.map(cRes=> ( resident.id == cRes.id ) ? resident : cRes )
      this.$toasted.success('Residente actualizado exitosamente', {position: 'bottom-left'})
    },
    deleteResident(id){
      if( window.confirm('¿Seguro que quieres eliminar el residente?') ){
        axios.delete('/residents/' + id).then(response=>{
            console.log( response );
            this.$toasted.success('Residente eliminado exitosamente', {position: 'bottom-left'})
            this.residents = this.residents.filter((resident)=>{
                return resident.id != id
            });
        },error=>{
          if( error.status == 403 ){
            this.$toasted.error('No tienes permisos para realizar esta acción', {'position':'bottom-left'})
          }
          this.errors = error.response.data.errors
        })
      }
    },
    appendResident(resident){
      this.residents.push( resident )
      this.$toasted.success('Residente registrado exitosamente', {position: 'bottom-left'})
    }
  },
  mounted(){
    axios.get('/residents/list').then((response)=>{
      this.residents = response.data.data
    });
  },
  created(){
    this.debouncedFilterResidents = _.debounce( this.filterResidents, 500 )
  }
} 
</script>
