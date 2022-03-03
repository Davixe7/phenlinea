<template>
  <div id="petitions">
    <h1>Solicitudes</h1>
    <hr>
    <div class="row">
      <div class="col-md-6 mb-3">
        <CreatePetition
          :isAdmin="user && !user.admin_id"
          :petition="currentPetition"
          @storePetition="storePetition"
          @petitionUpdated="refreshPetition"
          @unsetPetition="unsetPetition"
        />
      </div>
      <div ref="petitionsContainer" class="col-md-5 offset-md-1">
        <span class="form-section-title">
          Tus solicitudes
        </span>
        <ListPetitions
          :isAdmin="user && !user.admin_id"
          :petitions="petitions"
          @editPetition="editPetition"
          @deletePetition="deletePetition"
        />
      </div>
    </div>
  </div>
</template>

<script>
  import CreatePetition from './CreatePetition.vue'
  import ListPetitions  from './ListPetitions.vue'
  export default {
    name: 'Petitions',
    components: {CreatePetition, ListPetitions},
    data(){
      return {
        user: {},
        petitions: [],
        currentPetition: {}
      }
    },
    methods:{
      unsetPetition(){
        this.currentPetition = {}
      },
      refreshPetition(petition){
        this.petitions = this.petitions.map(p=>(petition.id == p.id) ? petition : p)
        this.currentPetition = petition
      },
      storePetition(petition){
        axios.post('/petitions', petition).then(response=>{
          this.$toasted.success('Solicitud generada exitosamente')
          this.petitions.unshift( response.data.data )
          this.currentPetition = {}
        },error=>{
          this.$toasted.error('Error al generar la solicitud')
        })
      },
      editPetition(petition){
        this.currentPetition = petition
      },
      deletePetition(petition){
        axios.delete(`/petitions/${petition.id}`).then(response=>{
          this.petitions = this.petitions.filter(_petition=> petition.id != _petition.id)
          this.$toasted.success('Solicitud eliminada exitosamente')
          this.currentPetition = {}
        },error=>{
          this.$toasted.error('Error al eliminar la solicitud')
        })
      },
      fetchPetitions(){
        this.loader = this.$loading.show({isFullPage:false, container: this.$refs.petitionsContainer, opacity: 0})
        axios.get('/petitions').then(response=>{
          this.petitions = response.data.data
        },error=>{
          this.$toasted.error('Error al cargar las solicitudes')
        }).then(()=>{
          this.loader.isActive = false
        })
      },
      fetchUser(){
        axios.get('/user').then(response=>{
          this.user = response.data.data
        },error=>{
          console.log(error);
        })
      }
    },
    mounted(){
      this.fetchUser();
      this.fetchPetitions()
    }
  }
</script>