<template>
  <div id="login-form">
    <div class="form-title">
      <img src="/img/logo.png" alt="" style="width: 144px;">
      <div class="d-flex align-items-center justify-content-center">
        <div>
          <a href="#" v-show="activeForm==2" @click.prevent="activeForm=1" class="mr-3">
            <i class="material-icons" style="width: 24px;">arrow_backwards</i>
          </a>
          <span>{{ activeForm == 1 ? 'Propietarios &amp; Residentes' : 'Recuperar Contraseña' }}</span>
        </div>
      </div>
    </div>
    <form v-show="activeForm==1" @submit.prevent="attemptLogin" ref="loginForm">
      <div class="form-group">
        <label for="unit">
          Seleccionar Unidad Residencial
        </label>
        <select id="unit" class="form-control" v-model="unit">
          <option v-for="u in units" :key="u.id" :value="u">
            {{ u.name }}
          </option>
        </select>
      </div>
      <div class="form-group">
        <label for="extension">Seleccionar apartamento</label>
        <select id="extension" v-model="extension" class="form-control" :class="{'is-invalid':errors.extension && errors.extension.length}">
          <option v-for="ext in extensions" :key="ext.id" :value="ext">{{ ext.name }}</option>
        </select>
        <span class="invalid-feedback" v-if="errors.extension && errors.extension.length">{{ errors.extension[0] }}</span>
      </div>
      <div class="form-group">
        <label for="password">Contraseña</label>
        <input type="password" id="password" class="form-control" :class="{'is-invalid':errors._email}" v-model="password" required>
        <span class="invalid-feedback" v-if="errors._email">{{ errors._email[0] }}</span>
      </div>
      <button class="btn btn-link pl-0" :class="{'text-danger': passwordForgotten}" type="button" @click="passwordForgotten = !passwordForgotten">
          {{ passwordForgotten ? 'Por favor pongase en contacto con su administración' : '¿Olvido su contraseña?'}}
      </button>
      <div class="form-group">
        <button type="submit" class="btn btn-secondary btn-login" :class="{disabled:loginIn}">
          <span>Acceder al sistema</span>
          <span v-show="loginIn" class="spinner-border spinner-border-sm ml-auto"></span>
        </button>
      </div>
    </form>
    <password v-show="activeForm==2" v-if="extension" :extension="extension.id" :phones="extension.phones" @passwordSent="activeForm=1"></password>
  </div>
</template>

<script>
import Password from './Password.vue';
export default {
  components: { Password },
  props: ['preloadUnits','preloadExtension'],
  watch:{
    unit(oldVal, newVal){
      if(newVal){
        this.fetchExtensions()
      }
    }
  }, 
  data(){return {
      passwordForgotten: false,
    activeForm:1,
    errors: {},
    units: [],
    extensions: [],
    phones: [],
    unit: {},
    extension: {},
    password: '',
    loginIn: false
  }},
  methods:{
    showPasswordForm(){
      if( this.extension && this.extension.id ) {
          this.activeForm =2
      }else {
          this.errors = {extension:['Por favor seleccione una extensión']}
      }
    },
    fetchExtensions(){
      axios.get(`/extensionslist/${this.unit.id}`).then(response=>{
        this.extensions = response.data.data
      });
    },
    attemptLogin(){
      if( ! this.$refs.loginForm.reportValidity() ){ return }
      this.loginIn = true
      let data = {
        'admin_id': this.unit.id,
        'extension_id': this.extension.id,
        '_email': this.unit.id + this.extension.name + '@phenlinea.com',
        'password': this.password
      }
      axios.post('/residents/login', data).then(
      response=>{
        window.location.href = "/";
      },error=>{
        this.errors = error.response.data.errors
      }).then(()=>{this.loginIn=false})
    }
  },
  mounted(){
    if( this.preloadUnits ){
      this.units = this.preloadUnits
    }
  }
}
</script>

<style lang="scss" scoped>
</style>