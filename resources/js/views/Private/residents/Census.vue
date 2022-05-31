<template>
  <div id="census">
    <div class="page-title">
      <h1>Censo del edificio</h1>
      <div class="d-inline-flex ml-auto align-items-center">
        <div v-if="user && extensions && extensions.length">
          <a :href="`/admins/${user.id}/extensions/export`" class="btn btn-export">
            <i class="material-icons mr-2" style="">arrow_circle_down</i>
            Exportar excel
          </a>
        </div>
        <SearchForm v-model="results"
          v-if="extensions && extensions.length"
          :collection="extensions"
          :attribute="'name'">
        </SearchForm>
      </div>
    </div>
    <div
      v-if="results && results.length"
      id="extensions-table-wrap" class="table-responsive">
      <table class="table">
        <thead>
          <th>Apto.</th>
          <th>Adultos</th>
          <th>Menores</th>
          <th>Mascotas</th>
          <th>Vehículos</th>
          <th>Útil</th>
          <th>Tel. propietario</th>
          <th>Citofonía 1</th>
          <th>Citofonía 2</th>
          <th class="text-right">Detalle</th>
        </thead>
        <tbody>
          <tr v-for="ext in results" :key="ext.id">
            <td>{{ ext.name }}</td>
            <td>{{ ext.adults }}</td>
            <td>{{ ext.minors }}</td>
            <td>{{ ext.pets_count }}</td>
            <td>{{ ext.vehicles }}</td>
            <td>{{ (ext.has_deposit) ? 'SÍ' : 'NO' }}</td>
            <td>{{ ext.owner_phone }}</td>
            <td>{{ ext.phone_1 }}</td>
            <td>{{ ext.phone_2 }}</td>
            <td>
              <a :href="`/extensions/${ext.id}/visitors`">
                <i class="material-icons">lock_open</i>
              </a>
              <a :href="`/census/${ext.id}/edit`">
                <i class="material-icons">visibility</i>
              </a>
              <a href="#" @click="deleteExtension(ext.id)">
                <i class="material-icons">delete</i>
              </a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    
    <div v-else-if="!loader.isActive" class="alert alert-info">No hay extensiones para mostrar</div>
    
    <div class="fab-container">
      <a
        href="/census/create"
        class="btn btn-primary btn-circle">
        <i class="material-icons">add</i>
      </a>
    </div>
    
  </div>
</template>

<script>
import SearchForm from './../SearchForm.vue'
export default {
  components: { SearchForm },
  name: 'Census',
  data(){
    return {
      user: null,
      extToEdit: null,
      editing: false,
      extensions: [],
      results: [],
      loader: {}
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
        axios.delete('/census/' + id).then((response)=>{
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
    
    this.loader = this.$loading.show()
    
    axios.get('census/list').then((response)=>{
      this.extensions = response.data.data
      this.results    = [...this.extensions]
      this.loader.hide()
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
  table th:last-child, table tr td:last-child {
    text-align: right;
  }
  .btn-export {
    font-size: 1em;
    font-weight: 400;
    color: darkgray;
    text-transform: none;
    border-radius: 5px;
    border: 1px solid lightgray;
    background: #fff;
    box-shadow: none;
    margin-right: 10px;
    height: 40px;
  }
</style>
