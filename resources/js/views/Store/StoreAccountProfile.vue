<template>
  <div class="row">
    <div class="col-md-3">
      <store-side-nav :commerce="commerce" v-model="activeForm" class="mb-3"/>
    </div>
    <div class="col-md-6">
      <h1>{{title}}</h1>
      <edit-store :commerce="commerce" :url="'/stores/'" v-show="activeForm=='profile'"/>
      <products :menu="commerce.menu" v-show="activeForm=='menu'"/>
      <commerce-password :commerce="commerce" v-show="activeForm=='password'"/>
      <div v-show="activeForm=='qr'">
        <v-img
          contain
          :src="commerce.qr.url"
          height="200"
          class="mb-3"
        />
        <v-text-field outlined
          label="URL"
          :value="commerce.permalink" :icon-append="link" readonly>
        </v-text-field>
        <v-btn href="/stores/get-qr" block dark>Descargar</v-btn>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['commerce'],
  data(){return{
    activeForm: 'profile',
    titles: {
      profile: 'Información básica',
      menu: 'Productos',
      password: 'Actualizar contraseña',
      qr: 'Código QR',
    }
  }},
  computed:{
    title(){
      return this.titles[this.activeForm]
    }
  }
}
</script>

<style lang="scss" scoped>
</style>
