<template>
  <div class="bills-list" v-if="bills && bills.length">
    <div class="bill-content" v-for="bill in bills">
      <div class="row d-flex align-items-center">
        <div class="title col-5">
          <h5>{{ bill.title }}</h5>
          <span v-if="description" class="description">
            {{ bill.description }}
          </span>
        </div>
        <div v-if="isAdmin" class="col-7 text-right">
          <div class="options-dropdown ml-auto">
            <div class="dropdown">
              <span class="dropdown-toggler" data-toggle="dropdown">
                <i class="material-icons">more_vert</i>
              </span>
              <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="#" @click.prevent="editBill(bill)">Editar</a>
                <a class="dropdown-item" href="#" @click.prevent="deleteBill(bill)">Eliminar</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="url">
          <a v-if="isAdmin" :href="bill.url">{{ bill.url }}</a>
          <a v-else :href="bill.url" class="btn btn-primary text-white">Click aquí</a>
        </div>
    </div>
  </div>
  <div v-else class="alert alert-info">
    <i class="material-icons">info</i> No hay enlaces para mostrar
  </div>
</template>

<script>
import CoolLightBox from 'vue-cool-lightbox'
export default {
  components: { CoolLightBox },
  name: 'BillsList',
  props: {
    isAdmin: {type: Boolean, default: false},
    bills:   {type: Array, default: ()=>[]}
  },
  data(){
    return {
      pictures: [],
      index: null
    }
  },
  computed:{
    lbPictures(){
      return this.pictures.map(p=>p.url)
    }
  },
  methods:{
    editBill(bill){
      this.$emit('editBill', bill)
    },
    deleteBill(bill){
      if( confirm('¿Seguro que quiere eliminar la factura?') ){
        this.$emit('deleteBill', bill)
      }
    }
  }
}
</script>

<style lang="sass" scoped>
  .bill-content
    border-radius: 3px
    background: #fff
    padding: 15px 20px 20px
    margin-bottom: 20px
    box-shadow: 0 1px 7px 1px rgba(0,0,0,.1)
    .title
      display: flex
      flex-flow: row nowrap
      h5
        margin: 0
      .options-dropdown i.material-icons
        cursor: pointer
        font-size: 1.25em
        color: #9f9f9f
    .description
      display: block
      margin-bottom: 10px
      
  .pictures-list
    display: flex
    flex-flow: row nowrap
  .picture-wrapper
    width: 120px
    overflow: hidden
    margin-right: 10px
    border-radius: 3px
    box-shadow: 0 1px 3px 1px rgba(0,0,0,.2)
    img
      height: 130px
      width: auto
</style>
