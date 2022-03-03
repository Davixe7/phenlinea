<template>
  <div id="bills">
    <h1>
      Directorio de Pagos
    </h1>
    <hr>
    <div class="row">
      <div class="col-md-5">
        <div class="card">
          <div class="card-body">
            <span class="form-section-title">Crear enlace de pago</span>
            <CreateBill
              :bill="bill"
              @storeBill="pushBill"
              @updateBill="refreshBill"/>
          </div>
        </div>
      </div>
      <div class="col-md-6 offset-md-1">
        <div class="form-row">
          <span class="col form-section-title">Todos los enlaces</span>
          <div class="col text-right">
            <i class="material-icons">receipt</i> 
          </div>
        </div>
        <BillsList
          :bills="bills"
          :isAdmin="user && !user.admin_id" 
          @deleteBill="deleteBill"
          @editBill="editBill"/>
      </div>
    </div>
  </div>
</template>

<script>
import CreateBill from './CreateBill.vue'
import BillsList  from './BillsList.vue'
export default {
  components: { CreateBill, BillsList },
  name: 'Bills',
  data(){
    return {
      bills: [],
      bill: {},
      user: {}
    }
  },
  watch:{
    extension(newVal){
      if(newVal && newVal.id){
        this.fetchBills()
        return
      }
      this.bills = []
    }
  },
  methods:{
    pushBill(bill){
      this.bills.push(bill)
    },
    editBill(bill){
      this.bill = bill
    },
    refreshBill(bill, keep){
      this.bills = this.bills.map( r => bill.id == r.id ? bill : r)
      if( keep ){
        this.bill = bill
      }
    },
    deleteBill(bill){
      axios.delete(`/bills/${bill.id}`).then(response=>{
        this.$toasted.success('Notificación eliminada exitosamente')
        this.bills = this.bills.filter( r => bill.id != r.id )
        this.bill = {}
      },error=>{
        console.log(error);
      })
    },
    fetchExtensions(){
      axios.get('/extensions/list').then(response=>{
        this.extensions = response.data.data
      },error=>{
        console.log(error.response.data);
      })
    },
    fetchBills(){
      axios.get('/bills/list').then(response=>{
        this.bills = response.data.data
      },error=>{
        console.log(error.response.data);
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
    this.fetchUser()
    this.fetchBills()
  }
}
</script>
