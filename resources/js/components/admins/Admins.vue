<template>
  <div id="admins" v-if="user">
    <div class="page-title">
      <h1>Administradores</h1>
      <SearchForm
        v-if="admins && admins.length"
        :collection="admins"
        :attribute="'name'"
        v-model="results"/>
    </div>
    
    <div v-if="results && results.length" class="table-responsive">
      <table class="table">
        <thead>
          <th>Nombre</th>
          <th>NIT</th>
          <th>Dirección</th>
          <th>Celular</th>
          <th>Celular 2</th>
          <th>Correo</th>
          <th>SMS</th>
          <th>Status</th>
          <th class="text-right" v-if="user.isAdmin">Opciones</th>
        </thead>
        <tbody>
          <tr v-for="admin in results">
            <td>{{ admin.name }}</td>
            <td>{{ admin.nit }}</td>
            <td>{{ admin.address }}</td>
            <td>{{ admin.phone }}</td>
            <td>{{ admin.phone_2 }}</td>
            <td>{{ admin.email }}</td>
            <td>{{ admin.month_sms_count }}</td>
            <td>{{ admin.status }}</td>
      
            <td v-if="user.isAdmin" class="text-right">
              <div class="btn-group">
                <a
                  :href="`/admin/admins/${admin.id}/edit-permissions`"
                  class="btn btn-sm btn-secondary text-white">
                  <i class="material-icons">lock</i>
                </a>
                <button class="btn btn-sm btn-success"   @click="editPayment(admin)"><i class="material-icons">list</i></button>
                <button class="btn btn-sm btn-secondary" @click="editAdmin(admin)"><i class="material-icons">edit</i></button>
                <button class="btn btn-sm btn-secondary" @click="deleteAdmin(admin.id)"><i class="material-icons">delete</i></button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div v-else class="alert alert-info">
        No hay administradores para mostrar
    </div>

    <div class="fab-container" v-if="user.isAdmin">
      <v-btn fab
        color="primary"
        data-toggle="modal"
        data-target="#adminEditModal"
        @click="()=>{editing=false; adminToEdit = null;}">
          <i class="material-icons">add</i>
      </v-btn>
    </div>

    <!-- Modal -->
    <div ref="AdminsModal" class="modal fade" id="adminEditModal" tabindex="-1" role="dialog" aria-labelledby="adminEditModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="adminEditModalLabel">
              <span v-if="!editing">Crear</span>
              <span v-else>Actualizar</span>
              administrador</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <create-admin
              :adminToEdit="adminToEdit"
              :editing="editing"
              @adminStored="appendAdmin"
              @adminUpdated="updateAdmin">
            </create-admin>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Modal -->
    <div ref="PaymentsModal" class="modal fade" id="paymentsModal" tabindex="-1" role="dialog" aria-labelledby="paymentsModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 id="paymentsModalLabel">Historial de Pagos</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <payments
              v-if="paymentToEdit"
              :admin="paymentToEdit"
              :user="user">
            </payments>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
export default {
  name: 'Admins',
  data(){
    return {
      adminToEdit: null,
      paymentToEdit: null,
      editing: false,
      user: null,
      admins: [],
      results: [],
      searchText: ''
    }
  },
  methods:{
    appendAdmin(admin){
      this.admins.push( admin )
      this.$toasted.success('Administrador registrado exitosamente', {position: 'bottom-left'})
      $( this.$refs.AdminsModal ).modal('hide')
    },
    editAdmin(admin){
      this.adminToEdit = admin
      this.editing = true
      $( this.$refs.AdminsModal ).modal('show');
    },
    editPayment(admin){
      this.paymentToEdit = admin
      this.editing = true
      $( this.$refs.PaymentsModal ).modal('show');
    },
    updateAdmin(admin){
      this.admins = this.admins.map((cExt)=>{
        return ( admin.id == cExt.id ) ? admin : cExt
      })
      this.$toasted.success('Administrador actualizado exitosamente', {position: 'bottom-left'})
      $( this.$refs.AdminsModal ).modal('hide')
    },
    deleteAdmin(id){
      if( window.confirm('¿Seguro que quieres eliminar al administrador?') ){
        axios.delete('/admin/admins/' + id).then((response)=>{
          this.admins = this.admins.filter((admin)=>{
            return admin.id != id
          });
        })
        .catch((error)=>{
          if( error.status == 403 ){
            this.$toasted.error('No tienes permisos para realizar esta acción', {'position':'bottom-left'})
          }
          this.errors = error.response.data.errors
        })
      }
    }
  },
  mounted(){
    axios.get('/user').then((response)=>{
      this.user = response.data.data
    });
    
    axios.get('/admin/admins/list').then((response)=>{
      this.admins  = response.data.data
      this.results = [...this.admins]
    });
  }
}
</script>

<style>
  .modal-title {
    font-size: 1.1em;
  }
</style>
