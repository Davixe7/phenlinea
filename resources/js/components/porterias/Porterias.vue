<template>
  <div id="porterias">
    <div class="row">
      <div class="col-md-9">
        <div class="page-title">
          <h1>Porterias</h1>
          <SearchForm
            v-show="porterias && porterias.length"
            :collection="porterias"
            :attribute="'name'"
            v-model="results"/>
        </div>
        
        <div class="table-responsive">
          <table class="table" v-if="results && results.length">
            <thead>
              <th>Admin</th>
              <th>Nombre</th>
              <th>Correo</th>
              <th>No. de extensiones</th>
              <th class="text-right">Opciones</th>
            </thead>
            <tbody>
              <tr v-for="porteria in results">
                <td>{{ porteria.admin ? porteria.admin.name : '' }}</td>
                <td>{{ porteria.name }}</td>
                <td>{{ porteria.email }}</td>
                <td>{{ porteria.extensions_count }}</td>
                <td class="text-right">
                  <div class="btn-group">
                    <a href="#" class="btn btn-sm btn-secondary" @click="editPorteria(porteria)">
                      <i class="material-icons">edit</i></a>
                    <a href="#" class="btn btn-sm btn-secondary" @click="deletePorteria(porteria.id)">
                      <i class="material-icons">delete</i></a>
                  </div> 
                </td>
              </tr>
            </tbody>
          </table>
          <div v-else class="alert alert-info">No hay porterias para mostrar</div>
        </div>
      </div>
    </div>

    <div class="fab-container">
      <button type="button" class="btn btn-primary btn-circle" data-toggle="modal" data-target="#exampleModal" @click="editing=false; extToEdit = null">
        <i class="material-icons">add</i>
      </button>
    </div>

    <!-- Modal -->
    <div ref="PorteriasModal" class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
              <span v-if="!editing">Crear</span>
              <span v-else>Actualizar</span>
              porteria</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <create-porteria
              :porteriaToEdit="porteriaToEdit"
              :editing="editing"
              @porteriaStored="appendPorteria"
              @porteriaUpdated="updatePorteria">
            </create-porteria>

          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import CreatePorteria from './CreatePorteria.vue'
import SearchForm from './../SearchForm.vue'

export default {
  name: 'Porterias',
  components: { CreatePorteria, SearchForm },
  data(){
    return {
      porteriaToEdit: null,
      editing: false,
      porterias: [],
      searchText: '',
      results: []
    }
  },
  methods:{
    editPorteria(porteria){
      this.porteriaToEdit = porteria
      this.editing = true
      $( this.$refs.PorteriasModal ).modal('show');
    },
    updatePorteria(porteria){
      this.porterias = this.porterias.map((cExt)=>{
        return ( porteria.id == cExt.id ) ? porteria : cExt
      })
      this.$toasted.success('Porteria actualizada exitosamente', {position: 'bottom-left'})
    },
    deletePorteria(id){
      if( window.confirm('¿Seguro que quieres eliminar la porteria?') ){
        axios.delete('/admin/porterias/' + id).then((response)=>{
            console.log( response );
            this.$toasted.success('Porteria eliminada exitosamente', {position: 'bottom-left'})
            this.porterias = this.porterias.filter((porteria)=>{
                return porteria.id != id
            });
        })
        .catch((error)=>{
          if( error.status == 403 ){
            this.$toasted.error('No tienes permisos para realizar esta acción', {'position':'bottom-left'})
          }
          this.errors = error.response.data.errors
        })
      }
    },
    appendPorteria(porteria){
      this.porterias.push( porteria )
      this.$toasted.success('Porteria registrada exitosamente', {position: 'bottom-left'})
    }
  },
  mounted(){
    axios.get('/admin/porterias/list').then(response=>{
      this.porterias = response.data.data
      this.results   = [...this.porterias]
    });
  }
} 
</script>
