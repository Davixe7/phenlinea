<template>
  <div>
    <div id="form" v-show="!sent">
      <form @submit.prevent="sendPassword" ref="passwordForm">
        <div class="form-group">
          <label for="#">Seleccionar Número telefónico</label>
          <ul class="phones">
            <li v-for="(phoneNumber,n) in phones" class="form-check">
              <input class="form-check-input" type="radio" name="exampleRadios" :id="`radio${n}`" :value="phoneNumber" v-model="phone">
              <label class="form-check-label" :for="`radio${n}`">{{ phoneNumber | obs }}</label>
            </li>
          </ul>
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-secondary btn-login d-flex align-items-center" :class="{disabled:sending}">
            <span>Enviar contraseña</span>
            <div class="ml-auto" style="line-height: 0;">
              <span v-show="sending" class="spinner-border spinner-border-sm ml-auto"></span>
              <span v-show="!sending">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-send"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
              </span>
          </div>
          </button>
        </div>
      </form>
    </div>
    
    <div id="alert-sent" v-show="sent">
      <div class="alert alert-success">
        <i class="material-icons">check</i>
        Enviamos su contraseña en un mensaje al número telefónico que indicó.
      </div>
      <div class="alert alert-info">
        <i class="material-icons">info</i>
        Si tiene problemas para recibir el mensaje, pongase en contacto con la administración de su unidad.
      </div>
      <button
        @click="$emit('passwordSent')"
        class="btn btn-secondary btn-login">
        Volver al ingreso
        <i class="material-icons ml-auto">arrow_forward</i>
      </button>
    </div>
    
  </div>
</template>

<script>
export default {
  props: ['phones', 'extension'],
  data(){return {
    errors: {},
    phone: '',
    sending: false,
    sent: false
  }},
  methods:{
    sendPassword(){
      if(this.phone){
        this.sending = true
        axios.post(`/extensions/${this.extension}/sendpassword`, {phone:this.phone}).then(
            response=>{
                this.sending = false
                this.sent = true
            },
            error=>{
                console.log(error);
            })
    }},
  },
  filters:{
    obs(phone){
      let p = phone.toString()
      return p.substring(0,3) + '*****' + p.substring( p.length - 2 )
    }
  },
  mounted(){
      this.phone = this.phones && this.phones.length ? this.phones[0] : null
  }
}
</script>

<style lang="scss" scoped>
  ul.phones {
    list-style-type: none;
    padding: 0;
    margin: 0;
    
    li label {
      user-select: none;
      cursor: pointer;
      margin: 0;
      color: #19191c !important;
    }
    li {
      display: flex;
      align-items: center;
      padding: 10px 10px 10px 15px;
      border: 1px solid lightgray;
      border-radius: 2px;
    }
    input {
      margin: 0 15px 0 0;
      position: relative;
    }
  }
</style>