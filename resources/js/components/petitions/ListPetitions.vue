<template>
  <div id="list-petitions">
    <table class="table" v-if="petitions && petitions.length">
      <thead>
        <th>apto.</th>
        <th>fecha</th>
        <th>título</th>
        <th>estado</th>
        <th>acciones</th>
      </thead>
      <tbody>
        <tr v-for="pet in petitions">
          <td>{{ pet.extension.name }}</td>
          <td>{{ pet.created_at }}</td>
          <td>{{ pet.title }}</td>
          <td>
            <tag :status="pet.status"/>
          </td>
          <td>
            <span @click="editPetition(pet)" class="btn-link btn-sm">
              <i class="material-icons">remove_red_eye</i>
            </span>
            <span v-if="isAdmin" @click="deletePetition(pet)" class="btn-link btn-sm">
              <i class="material-icons">delete</i>
            </span>
          </td>
        </tr>
      </tbody>
    </table>
    <div v-else class="alert alert-info">
      <i class="material-icons">info</i> 
      No hay solicitudes para mostrar
    </div>
  </div>
</template>

<script>
import Tag from './../Tag.vue'
export default {
  components: {Tag},
  name: 'ListPetitions',
  data(){ return {
    errors: {},
  }},
  props: {
    isAdmin: {type:Boolean, default: false},
    petitions: {type: Array, default: []}
  },
  methods:{
    editPetition(petition){
      this.$emit('editPetition', petition)
    },
    deletePetition(petition){
      if(confirm('¿Seguro que desea eliminar la solicitud?')){
        this.$emit('deletePetition', petition)
      }
    }
  }
}
</script>

<style lang="sass">
  .form-section-title
    display: block
    font-size: .9em
    text-transform: uppercase
    color: gray
    margin-bottom: 10px

  .tag
    border-radius: 5px
    padding: 5px 10px
    font-weight: 500
    font-size: .9em
    color: #fff
    background: gray

  .tag.tag-sm
    font-size: .8em
    padding: 2px 5px
    border-radius: 3px

  .btn-link.btn-sm
    i.material-icons
      font-size: 1.5em
</style>
