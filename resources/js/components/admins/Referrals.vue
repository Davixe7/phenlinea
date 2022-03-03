<template>
  <div id="referrals">
    <h1>Unidades residenciales</h1>
    <div class="row mt-3">
      <div class="col-md-8 table-responsive">
        <table class="table" v-if="user && admins && admins.length">
          <thead>
            <th>Nombre</th>
            <th>Teléfono</th>
            <th>Dirección</th>
            <th class="text-right">Estado</th>
          </thead>
          <tbody>
            <tr v-for="admin in admins" @click="setAdmin(admin.id)">
              <td>{{ admin.name }}</td>
              <td>{{ admin.phone }}</td>
              <td>{{ admin.address }}</td>
              <td>
                <span
                  :class="['tag', cssStatuses[ admin.solvencia ] ]">
                  {{ admin.solvencia | stringStatus }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>
        
        <div v-else class="alert alert-info">
          <div class="row">
            <div class="col-1">
              <i class="material-icons">info</i>
            </div>
            <div class="col-11">
              No hay referidos para mostrar
            </div>
          </div>
        </div>
        
        <!-- Balances panel -->
        <div v-if="user" class="balancesPanel">
          <div>
            <span class="digit">{{ actCount }}</span>
            <span class="desc">Unidades activas</span>
          </div>
          <div>
            <span class="digit">{{ user.rate }}</span>
            <span class="desc">Monto Unitario</span>
          </div>
          <div>
            <span class="digit">{{ total }}</span>
            <span class="desc">Total</span>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div v-if="user && admin" class="card">
          <div class="card-header">
              <h4 style="color: #000;">Historial de pagos</h4>
          </div>
          <div class="card-body">
            <payments
              :user="user"
              :admin="admin"
            ></payments>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Payments from './../admins/Payments'
export default {
  name: 'Referrals',
  components: {Payments},
  data(){
    return {
      admins: [],
      user: null,
      admin: null,
      cssStatuses: ['tag-success', 'tag-caution', 'tag-danger', 'tag-danger-alt']
    }
  },
  filters: {
    stringStatus: function (value) {
      let statuses = ['activo', 'pendiente', 'suspendido', 'suspendido']
      return statuses[ value ]
    }
  },
  computed:{
    actCount(){
      let actives = this.admins.filter((a)=>{ return a.solvencia == 0 || a.solvencia == 3 })
      return actives.length
    },
    pendCount(){
      let pend = this.admins.filter((a)=>{ return a.solvencia == 1 })
      return pend.length
    },
    susCount(){
      let sus = this.admins.filter((a)=>{ return a.solvencia == 2 })
      return sus.length
    },
    total(){
      return this.actCount * this.user.rate
    }
  },
  methods:{
    setAdmin(id){
      this.admins.filter((a)=>{
        if( a.id == id ){
          this.admin = a
        }
      })
    }
  },
  mounted(){
    axios.get('/user').then(response=>{
      this.user = response.data.data
    })
    axios.get('/admins').then(response=>{
      console.log(response.data.data)
      this.admins = response.data.data
    })
  }
}
</script>

<style lang="css" scoped>
  .digit {
    font-size: 24px;
    font-weight: 600;
    color: #282828;
    display: block;
  }
  .desc {
    font-size: 12px;
    font-weight: 400;
    color: #282828;
    text-transform: uppercase;
  }
  .balancesPanel {
    display: flex;
    flex-flow: row nowrap;
  }
  .balancesPanel > div {
    padding: 10px 15px;
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
</style>
