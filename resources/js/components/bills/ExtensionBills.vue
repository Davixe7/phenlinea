<template>
  <div id="bills">
    <h1>Enlaces</h1>
    <hr>
    <div class="row">
      <div class="col-md-6">
        <span class="form-section-title">Todos los enlaces</span>
        <div ref="billsContainer" style="min-height=100px;">
          <BillsList :bills="bills" @editBill="editBill"/>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import BillsList  from './BillsList.vue'
export default {
  components: { BillsList },
  name: 'ExtensionBills',
  data(){
    return {
      bills: [],
      bill: {},
      extension: null,
      user: {},
      loader: {}
    }
  },
  methods:{
    editBill(bill){
      this.bill = bill
    },
    refreshBill(bill, keep){
      this.bills = this.bills.map( r => bill.id == r.id ? bill : r)
      if( keep ){
        this.bill = bill
      }
    },
    fetchBills(){
      this.loader = this.$loading.show({isFullPage:false, container: this.$refs.billsContainer, opacity: 0})
      axios.get('bills/list').then(response=>{
        this.bills = response.data.data
      },error=>{
        console.log(error.response.data);
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
    this.fetchUser()
    this.fetchBills()
  }
}
</script>

<style lang="css" scoped>
</style>
