<template>
  <div id="extensions">
    
    <!-- Header -->
    <div class="row">
      <div class="col-lg-7">
        <div class="page-title">
          <h1>Extensiones</h1>
          <SearchForm
            v-show="extensions && extensions.length"
            :collection="extensions"
            :attribute="'name'"
            v-model="results"/>
        </div>
      </div>
    </div>
    
    <div class="row">
      <div class="col-lg-7 extensions-container">
        <div v-if="results && results.length" class="table-responsive" id="extensions-table-wrap">
          <table class="table">
            <thead>
              <th>Unidad</th>
              <th>Nro. Aptto</th>
              <th>Línea 1</th>
              <th>Línea 2</th>
              <th class="text-right">Opciones</th>
            </thead>
            <tbody>
              <tr v-for="ext in results">
                <td>{{ user.name }}</td>
                <td>{{ ext.name }}</td>
                <td class="filled">{{ ext.phone_1 }}</td>
                <td>
                  <span v-if="ext.phone_2" class="filled">{{ ext.phone_2 }}</span>
                  <span v-else class="empty">SIN ASIGNAR</span>
                </td>
                <td class="text-right">
                  <div class="btn-group">
                    <button class="btn btn-sm btn-secondary" @click="editExtension(ext)">
                      <i class="material-icons">edit</i>
                    </button>
                    <button class="btn btn-sm btn-secondary" @click="deleteExtension(ext.id)">
                      <i class="material-icons">delete</i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div v-else class="alert alert-info">No hay extensiones para mostrar</div>
        
        <!-- Action Buttons -->
        <div class="row">
          <div class="col pt-2">
            <a v-if="user && extensions.length" :href="`/admins/${user.id}/extensions/export`" class="btn btn-success">
              <i class="material-icons">cloud_download</i> 
              Exportar Excel
            </a>
          </div>
          
          <div class="col pt-2">
            <button v-if="extensions && extensions.length" type="button" class="btn btn-secondary" data-toggle="modal" data-target="#exampleModal2">
              SMS Masivo <i class="material-icons">message</i>
            </button>
          </div>
          
          <div class="col text-right">
            <button type="button" class="btn btn-primary btn-circle" data-toggle="modal" data-target="#exampleModal" @click="editing=false; extToEdit = null">
              <i class="material-icons">add</i>
            </button>
          </div>
        </div>
      </div>
      
      <div class="col-lg-4 offset-lg-1">
        <div class="card">
          <div class="card-header"><h4>Historial de pagos</h4></div>
          <div class="card-body">
            <Payments
            v-if="user"
            :admin="user"
            :user="user"
            ></Payments>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Modal -->
    <div ref="ExtensionsModal" class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
              <span v-if="!editing">Crear</span>
              <span v-else>Actualizar</span>
              extensión</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <create-extension
              :extToEdit="extToEdit"
              :editing="editing"
              @extensionStored="appendExtension"
              @extensionUpdated="updateExtension">
            </create-extension>
            
          </div>
        </div>
      </div>
    </div>
    
    <!-- Modal -->
    <div ref="SMSModal" class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModal2Label" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModal2Label">
              SMS Masivo</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <messages :extensions="extensions"></messages>
          </div>
        </div>
      </div>
    </div>
    
  </div>
</template>

<script>
import CreateExtension from './CreateExtension.vue'
import Messages from './Messages.vue'
import Payments from './../admins/Payments.vue'
import SearchForm from './../SearchForm.vue'

export default {
  name: 'Extensions',
  components: { CreateExtension, Payments, Messages, SearchForm },
  data(){
    return {
      user: null,
      extToEdit: null,
      editing: false,
      extensions: [],
      results: [],
      searchText: ''
    }
  },
  methods:{
    editExtension(ext){
      this.extToEdit = ext
      this.editing = true
      $( this.$refs.ExtensionsModal ).modal('show');
    },
    updateExtension(ext){
      this.extensions = this.extensions.map((cExt)=>{
        return ( ext.id == cExt.id ) ? ext : cExt
      })
      this.$toasted.success('Extensión actualizada exitosamente', {position:'bottom-left'})
    },
    deleteExtension(id){
      if( window.confirm('¿Seguro que quieres eliminar la extensión?') ){
        axios.delete('extensions/' + id).then((response)=>{
          this.extensions = this.extensions.filter((ext)=>{
            return ext.id != id
          });
          this.$toasted.success('Extensión eliminada', {position: 'bottom-left'})
        })
        .catch((error)=>{
          console.log( error.response.data );
        })
      }
    },
    appendExtension(ext){
      this.extensions.push( ext )
      this.$toasted.success('Extensión creada exitosamente', {position:'bottom-left'});
    },
    updateExtensions(ext){
      this.extensions = ext
    }
  },
  mounted(){
    axios.get('user').then((response)=>{
      this.user = response.data.data
    });
    axios.get('extensions/list').then((response)=>{
      this.extensions = response.data.data
      this.results    = [...this.extensions]
    });
  }
}
</script>

<style>
  .extensions-container {
    height: calc(100vh - 112px);
    position: relative;
    padding-bottom: 20px;
  }
  #extensions-table-wrap {
    height: calc(100% - 50px);
    margin-bottom: 10px;
    overflow: auto;
  }
  .filled {
    color: #06a906;
  }
  .empty {
    color: #c0392b;
  }
  table th:nth-child(8), table th:nth-child(9),
  table td:nth-child(8), table td:nth-child(9) {
    text-align: center;
  }
</style>
