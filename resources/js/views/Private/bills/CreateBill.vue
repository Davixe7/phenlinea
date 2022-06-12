<template>
  <div id="create-bill">
    <form ref="storeBillForm" @submit.prevent="storeBill">
      <div class="form-group">
        <label for="title">Concepto</label>
        <input type="text" class="form-control" v-model="title" placeholder="Concepto" required>
      </div>
      <div class="form-group">
        <label for="title">Enlace</label>
        <input type="url" class="form-control" v-model="url" placeholder="Ej: http://pasarela-de-pago.com" required>
      </div>
      
      <div class="text-right">
        <button type="button" @click="clearForm" class="btn btn-link">Cancelar</button>
        <button type="submit" v-show="!editing"  class="btn btn-primary">Guardar</button>
        <button type="button" v-show="editing"  @click="updateBill" class="btn btn-primary">Actualizar</button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  props: {
    extension: {
      type: Object,
      default:()=>{}
    },
    bill: {
      type: Object,
      default:()=>{}
    }
  },
  name: 'CreateBill',
  data(){
    return {
      title: '',
      url: '',
      editing: false,
    }
  },
  watch:{
    bill(newVal){
      if( newVal && newVal.id ){
        this.title = newVal.title
        this.url = newVal.url
        this.editing = true
        return
      }
      this.clearForm()
    }
  },
  methods:{
    storeBill(){
      if( this.$refs.storeBillForm.reportValidity() ){
        let data = this.loadData()
        axios.post('bills', data).then(response=>{
          this.$emit('storeBill', response.data.data)
          this.$toasted.success('Notificación registrada exitosamente')
        },error=>{
          console.log(error);
        }).then(()=>{
          this.clearForm()
        })
      }
    },
    updateBill(){
      let data = this.loadData()
      data.append('_method', 'PUT')
      axios.post(`bills/${this.bill.id}`, data).then(response=>{
        this.$emit('updateBill', response.data.data)
        this.$toasted.success('Notificación actualizada exitosamente')
      },error=>{
        console.log(error);
      }).then(()=>{
        this.clearForm()
      })
    },
    loadData(){
      let data = new FormData()
      data.append('title', this.title)
      data.append('url',   this.url)
      return data
    },
    clearForm(){
      this.title = ''
      this.url = ''
      this.editing = false
    }
  }
}
</script>

<style lang="sass" scoped>
  .extension-id
    font-size: 1.2em
    font-weight: 500
  .undefined-extension
    font-weight: 500
    color: #4d889a
    font-size: 1em
</style>
