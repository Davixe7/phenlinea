<template>
  <div id="admins-password">
    <div class="form-title">
      <img src="/img/logo.png" alt="site-logo" style="width: 144px;">
      <div class="d-flex align-items-center justify-content-center">
        <span>Recuperar Contraseña</span>
      </div>
    </div>
    <form v-show="!passwordSent" ref="AdminPasswordForm" @submit.prevent="sendPassword">
      <div class="form-group">
        <label for="admin">Seleccione Unidad</label>
        <select v-if="admins && admins.length" v-model="admin" class="form-control" required>
          <option v-for="admin in admins" :value="admin">{{ admin.name }}</option>
        </select>
      </div>
      
      <div v-if="admin && admin.phones" class="form-group">
        <label for="phone">Seleccione número telefónico</label>
        <label class="radio-list-item" v-for="(phoneNumber,n) in admin.phones" :for="`phone${n}`">
          <span class="ob-phone">{{ phoneNumber | obs}}</span>
          <input type="radio" :value="n" v-model="phone">
        </label>
      </div>
      
      <div class="form-group">
        <button class="btn btn-submit" type="submit">Enviar</button>
      </div>
    </form>
    
    <div id="alert-sent" v-show="passwordSent">
      <div class="alert alert-success">
        <i class="material-icons">check</i>
        Enviamos su contraseña en un mensaje al número telefónico que indicó.
      </div>
      <div class="alert alert-info">
        <i class="material-icons">info</i>
        Si tiene problemas para recibir el mensaje, pongase en contacto con la administración de su unidad.
      </div>
      <a
        href="/admins/login"
        class="btn btn-secondary btn-login">
        Volver al ingreso
        <i class="material-icons ml-auto">arrow_forward</i>
      </a>
    </div>
  </div>
</template>

<script>
export default {
  props: ['admins'],
  data(){return{
    admin: {},
    phone: '',
    sending: false,
    passwordSent: false
  }},
  methods:{
    sendPassword(){
      if( this.$refs.AdminPasswordForm.reportValidity() ){
        this.sending = true
        let phone = this.admin.phones[this.phone]
        axios.post(`/admins/${this.admin.id}/sendpassword`, {phone}).then(response=>{
          this.passwordSent = true
        },error=>{
          console.log(error);
        })
      }
    }
  },
  filters:{
    obs(phone){
      let p = phone.toString()
      return p.substring(0,3) + '*****' + p.substring( p.length - 2 )
    }
  },
}
</script>

<style lang="scss" scoped>
  .btn-submit {
    font-size: 1.25em;
    font-weight: 600;
    color: #fff;
    border: none;
    background: #000;
    border-radius: 2px;
    padding: 7px 24px;
    width: 100%;
    margin: 60px 0;
  }
  .radio-list-item {
    display: flex;
    align-items: center;
    input[type=radio]{
      margin-left: auto;
    }
  }
</style>
