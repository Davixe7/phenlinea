<template>
  <v-card width="256" tile>
    <v-navigation-drawer permanent>
      <v-system-bar></v-system-bar>
      <v-list>
        <v-list-item two-line>
          <v-list-item-avatar v-if="commerce.logo" class="elevation-2">
            <img :src="commerce.logo.url">
          </v-list-item-avatar>

          <v-list-item-content>
            <v-list-item-title>{{commerce.name}}</v-list-item-title>
            <v-list-item-subtitle>{{ commerce.email }}</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
      </v-list>
      <v-divider></v-divider>
      <v-list nav dense>
        <v-list-item-group v-model="item" color="primary">
          <v-list-item v-for="(item, i) in items" :key="i" @click="()=>{ (item.value == 'exit') ? logout() : $emit('input', item.value) }">
            <v-list-item-icon>
              <v-icon v-text="item.icon"></v-icon>
            </v-list-item-icon>

            <v-list-item-content>
              <v-list-item-title v-text="item.text"></v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list-item-group>
      </v-list>
    </v-navigation-drawer>
  </v-card>
</template>

<script>
export default {
  props: ['commerce'],
  methods:{
      logout(){
          axios.post('/stores/logout').then(res=>{
              window.location.href = "https://phenlinea.com"
          })
      }
  },
  data(){return{
    item:{},
    items: [
      {text:'Perfil',icon:'account_box',value:'profile'},
      {text:'Catálogo',icon:'menu_book',value:'menu'},
      {text:'Seguridad',icon:'lock',value:'password'},
      {text:'Código QR',icon:'qr_code_2',value:'qr'},
      {text:'Salir',icon:'exit_to_app',value:'exit'},
    ]
  }}
}
</script>

<style lang="css">
  hr {
    margin: 0;
  }
  .v-list-item__content {
    flex: 1 1 100%;
  }
  .v-list-item__icon:first-child {
    margin-right: 32px;
  }
  .v-navigation-drawer {
    width: 100%;
  }
</style>
