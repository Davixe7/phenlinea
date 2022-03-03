<template>
  <div id="store-menu">
    <v-card tile>
      <v-card-title style="padding: 0 20px; border-bottom: 1px solid #efefef;">
        Productos
        <v-spacer></v-spacer>
        <search-form v-show="menu && menu.length" :collection="products" :attribute="'name'" v-model="results" style="width: fit-content; margin-left: auto;"/>
      </v-card-title>
    <v-list
      :disabled="false"
      :dense="true"
      :two-line="true"
      :shaped="true"
      :avatar="true"
      :rounded="true"
    >
      <v-list-item-group v-model="item" color="primary">
        <v-list-item
          v-for="(item, i) in results"
          :key="i"
          :inactive="false"
          @click="dialog=true; selectedItem=item"
        >
          <v-list-item-avatar>
            <v-img :src="item.avatar"></v-img>
          </v-list-item-avatar>
          <v-list-item-content>
            <v-list-item-title v-html="item.title"></v-list-item-title>
            <v-list-item-subtitle v-html="item.subtitle"></v-list-item-subtitle>
          </v-list-item-content>
          <v-list-item-action>
            <v-btn icon @click="dialog=true; selectedItem=item"><v-icon>open_in_new</v-icon></v-btn>
          </v-list-item-action>
        </v-list-item>
      </v-list-item-group>
    </v-list>
  </v-card>
  <v-dialog v-model="dialog" width="400">
    <v-card v-if="selectedItem" color="#6BA6CD" dark>
      <v-carousel height="250" :show-arrows="false">
        <v-carousel-item
          v-for="(picture,i) in selectedItem.pictures"
          :key="i"
          :src="'/' + picture.url "
          reverse-transition="fade-transition"
          transition="fade-transition"
        ></v-carousel-item>
      </v-carousel>
      <v-card-title class="headline grey lighten-2">
        {{ selectedItem.name }}
      </v-card-title>
      <v-card-text>
        $ <b>{{ selectedItem.price }}</b> - {{ selectedItem.description }}
      </v-card-text>
      <v-divider></v-divider>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="primary" text @click="dialog = false">
          Cerrar
        </v-btn>
        <v-btn dark v-if="waLink" :href="waLink" target="_blank" @click="dialog = false">
          <v-img :src="'/img/icons8-whatsapp.svg'" :contain="true" height="30"></v-img>
          Pedir a domicilio
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
  </div>
</template>

<script>
export default {
  props:['menu','commerce'],
  data(){return {
    item: {},
    selectedItem: null,
    results: [],
    disabled: false,
    inactive: false,
    dialog: false
  }},
  computed:{
    waLink(){
      if( this.commerce && this.commerce.phone_1 ){
        return `https://api.whatsapp.com/send?phone=${this.commerce.phone_1}&text=Hola,%20estoy%20interesad@%20en%20${this.selectedItem.name}`
      }
      return null
    },
    products(){
      return this.menu.map(p=>{
        return {
          ...p,
          title: p.name,
          subtitle: `<span class="price">${p.price}</span> — ${p.description}`,
          avatar: p.main_picture ? '/' +  p.main_picture.url : ''
        }
      })
    }
  },
  mounted(){
    this.results = [...this.products]
    this.item = this.results[0]
  }
}
</script>

<style lang="scss">
  .price {
    font-weight: 600;
    color: #000;
  }
  .v-avatar.v-list-item__avatar {
    margin-right: 15px;
  }
</style>
