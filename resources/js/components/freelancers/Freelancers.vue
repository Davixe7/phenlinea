<template>
  <div id="freelancers">
    <div class="row">
      <div class="col-md-8">
        <div class="page-title">
          <h1>Freelancers</h1>
          <SearchForm
            v-show="freelancers && freelancers.length"
            :collection="freelancers"
            :attribute="'name'"
            v-model="results"/>
        </div>
        
        <table class="table table-responsive" v-if="results && results.length">
          <thead>
            <th>Nombre</th>
            <th>Teléfono</th>
            <th>Correo</th>
            <th>Tarifa por unidad</th>
            <th>No. de referidos</th>
            <th class="text-right">Opciones</th>
          </thead>
          <tbody>
            <tr v-for="freelancer in results">
              <td>{{ freelancer.name }}</td>
              <td>{{ freelancer.phone }}</td>
              <td>{{ freelancer.email }}</td>
              <td>{{ freelancer.rate }}</td>
              <td>{{ freelancer.referrals_count }}</td>
              <td class="text-right">
                <div class="btn-group">
                  <button class="btn btn-sm btn-secondary" @click="editFreelancer(freelancer)">
                    <i class="material-icons">edit</i>
                  </button>
                  <button class="btn btn-sm btn-secondary" @click="deleteFreelancer(freelancer.id)">
                    <i class="material-icons">delete</i>
                  </button>
                </div> 
              </td>
            </tr>
          </tbody>
        </table>
        <div v-else class="alert alert-info">No hay freelancers para mostrar</div>
      </div>
    </div>

    <div class="fab-container">
      <button type="button" class="btn btn-primary btn-circle" data-toggle="modal" data-target="#exampleModal" @click="editing=false; extToEdit = null">
        <i class="material-icons">add</i>
      </button>
    </div>

    <!-- Modal -->
    <div ref="FreelancersModal" class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
              <span v-if="!editing">Crear</span>
              <span v-else>Actualizar</span>
              freelancer</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <create-freelancer
              :freelancerToEdit="freelancerToEdit"
              :editing="editing"
              @freelancerStored="appendFreelancer"
              @freelancerUpdated="updateFreelancer">
            </create-freelancer>

          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import CreateFreelancer from './CreateFreelancer.vue'
import SearchForm from './../SearchForm.vue'

export default {
  name: 'Freelancers',
  components: { CreateFreelancer, SearchForm },
  data(){
    return {
      freelancerToEdit: null,
      editing: false,
      freelancers: [],
      results: [],
    }
  },
  methods:{
    editFreelancer(freelancer){
      this.freelancerToEdit = freelancer
      this.editing = true
      $( this.$refs.FreelancersModal ).modal('show');
    },
    updateFreelancer(freelancer){
      this.freelancers = this.freelancers.map((cExt)=>{
        return ( freelancer.id == cExt.id ) ? freelancer : cExt
      })
      this.$toasted.success('Freelancer actualizada exitosamente', {position: 'bottom-left'})
    },
    deleteFreelancer(id){
      if( window.confirm('¿Seguro que quieres eliminar la freelancer?') ){
        axios.delete('/admin/freelancers/' + id).then((response)=>{
            this.$toasted.success('Freelancer eliminada exitosamente', {position: 'bottom-left'})
            this.freelancers = this.freelancers.filter((freelancer)=>{
                return freelancer.id != id
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
    appendFreelancer(freelancer){
      this.freelancers.push( freelancer )
      this.$toasted.success('Freelancer registrada exitosamente', {position: 'bottom-left'})
    }
  },
  mounted(){
    axios.get('/admin/freelancers/list').then(response=>{
      this.freelancers = response.data.data
      this.results     = [...this.freelancers]
    });
  }
} 
</script>
