<template>
  <div id="payments">
    <div class="header">
      <i class="material-icons">supervised_user_circle</i>
      <div class="user-info">
        <div class="row">
          <div class="col d-flex align-items-end"><h4 style="color: #000;">{{ admin.name }}</h4></div>
          <div class="col">
            <select v-if="payments && payments.length" class="d-block ml-auto" v-model="year">
              <option value="2019">2019</option>
              <option
                v-for="n in pastYears"
                :value="2019 + n"> 
                {{ 2019 + n }}
              </option>
            </select>
          </div>
        </div>
        <div class="address">{{ admin.address }}</div>
      </div>
    </div>
    <ul v-if="payment" ref="PaymentsList" id="payments-list" class="payments-list">
      <li v-for="n in 12">
        <span>{{ months[n-1] }}</span>
        <span>
          <span
          v-if="!editing"
          :class="['tag', cssStatuses[ payment['m'+n] ] ]">
          {{ payment['m'+n] | stringStatus }}
        </span>
        <span v-else>
          <select v-model="upPayment['m'+n]">
            <option v-for="status in 4" :value="status - 1">{{ status - 1 | stringStatus}}</option>
          </select>
        </span>
      </span>
    </li>
  </ul>
  <div v-else class="alert alert-info px-2 mt-2">
    <i class="material-icons mr-2">error_outline</i> No hay registro de pagos para el año actual
  </div>
  <div v-if="user.isAdmin" class="text-right">
    <button v-if="!editing" @click="editing = true"  class="btn btn-link">EDITAR</button>
    <button v-if="editing"  @click="editing = false" class="btn btn-link">CANCELAR</button>
    <button v-if="editing"  @click="updatePayment" class="btn btn-primary">ACTUALIZAR</button>
  </div>
</div>
</template>

<script>
export default {
  name: 'Payments',
  props: {'user': Object, 'admin': Object},
  data(){
    return {
      months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
      cssStatuses: ['tag-success', 'tag-caution', 'tag-danger', 'tag-danger-alt'],
      editing: false,
      isAdmin: true,
      
      payments: [],
      payment: null,
      upPayment: {},
      
      year: new Date().getFullYear(),
      currentYear: new Date().getFullYear()
    }
  },
  filters: {
    stringStatus: function (value) {
      let statuses = ['activo', 'pendiente', 'suspendido', 'suspendido']
      return statuses[ value ]
    }
  },
  computed:{
    pastYears(){
      return this.currentYear - 2019
    }
  },
  watch:{
    admin(){
      this.fetchPayments()
    },
    year(newVal){
      this.payment = this.payments.filter(f=>f.year == newVal + '-01-01')[0]
      this.upPayment = {...this.payment}
    },
  },
  methods:{
    fetchPayments(){
      let loader = this.$loading.show({
        container: this.$refs.PaymentsList,
        canCancel: false,
        width: 40,
        height: 40,
      })
      
      let adminId = (this.user.admin_id || this.admin.id)
      
      axios.get(`/admins/${adminId}/payments`).then(response=>{
        this.payments   = [...response.data.data]
        this.payment = this.payments.length ? this.payments[ this.payments.length -1 ] : null
        this.upPayment = {...this.payment}
        loader.hide()
      })
    },
    updatePayment(){
      let data = {
        admin_id : this.upPayment.admin_id,
        _method  : 'PUT'
      }
      for (var i = 1; i < 13; i++){
        data['m'+i] = this.upPayment['m'+i]
      }
      axios.post(`/admin/payments/${this.upPayment.id}`, data).then(response => {
        this.$toasted.success('Actualizado con éxito', {'position':'bottom-left'})
        this.payment   = {...response.data.data}
        this.upPayment = {...response.data.data}
        this.editing   = false
      })
    },
  },
  mounted(){
    this.fetchPayments()
  }
}
</script>

<style>
  ul.payments-list {
    padding: 0;
    list-style-type: none;
  }
  ul.payments-list li {
    display: flex;
    flex-flow: row nowrap;
    padding: 10px 0 10px 5px;
  }
  ul.payments-list li > span {
    flex: 1;
  }
  ul.payments-list li > span:first-child {
    font-weight: 500;
  }
  ul.payments-list li > span:last-child {
    text-align: right;
  }
  .tag {
    display: inline-block;
    border-radius: 2px;
    padding: 3px 12px;
    color: #fff;
    text-transform: uppercase;
    font-size: 11px;
  }
  .tag.tag-success {
    background: #05e46e;
  }
  .tag.tag-caution {
    background: #ffb80f;
  }
  .tag.tag-danger {
    background: #df2626;
  }
  .tag.tag-danger-alt {
    position: relative;
    background: #df2626;
  }
  .tag.tag-danger-alt:after {
    content:'';
    position: absolute;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    top: -5px;
    right: -5px;
    display: block;
    background: #05e46e;
  }
  
  
  .header {
    display: flex;
    flex-flow: row nowrap;
  }
  .header > i {
    flex: 0;
    font-size: 40px;
    color: #a1a1a1;
  }
  .header > .user-info {
    display: flex;
    flex-flow: column;
    justify-content: center;
    padding-left: 10px;
    width: 100%;
  }
  .header > .user-info h4 {
    margin-bottom: 0;
    line-height: 1em;
    font-size: 1.1em;
  }
  .header > .user-info .address {
    color: #606060;
    font-size: 13px;
    line-height: 1em;
  }
  .btn.btn-link {
    text-decoration: none;
    box-shadow: none;
  }
</style>
