<template>
  <div id="store-menu">
    <v-card tile>
      <v-card-title style="padding-left: 20px; padding-right: 20px; border-bottom: 1px solid #efefef;">
        Productos
        <v-spacer></v-spacer>
        <search-form v-show="menu && menu.length" :collection="products" :attribute="'name'" v-model="results" style="width: fit-content; margin-left: auto;"/>
      </v-card-title>
    <v-container>
        <v-row>
        <v-col
            :sm="4" cols="6" class="mb-3"
            v-for="(item, i) in results"
            :key="i"
            :inactive="false"
        >
            <v-card @click="dialog=true; selectedItem=item" :elevation="0">
                <v-img
                    max-height="260"
                    :src="item.avatar">
                </v-img>
                <v-card-title style="font-size: 1rem; padding: 0 .9rem; justify-content: center; text-transform: uppercase;">
                    {{ item.name }}
                </v-card-title>
                <v-card-text class="text-center font-weight-black">
                    $ {{ item.price }}
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>
    </v-container>
  </v-card>
  <v-dialog v-model="dialog" width="400">
    <v-card v-if="selectedItem" color="#fff">
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
