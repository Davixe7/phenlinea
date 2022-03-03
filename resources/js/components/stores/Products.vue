<template>
  <div id="store-menu">
    <v-card>
      <v-data-table
        :headers="headers"
        :items="items"
        :search="search"
      >
        <template v-slot:top>
          <v-toolbar flat color="white">
            <v-text-field
            class="p-0 m-0"
            v-model="search"
            append-icon="search"
            label="Buscar..."
            single-line
            hide-details
            ></v-text-field>
            <v-divider class="mx-4" inset vertical></v-divider>
            <v-dialog v-model="dialog" max-width="500px" @click:outside="editedProduct=null">
              <template v-slot:activator="{ on, attrs }">
                <v-btn color="primary" dark class="mb-2" v-bind="attrs" v-on="on">Añadir</v-btn>
              </template>
              <v-card>
                <v-card-title>
                  <span class="headline">{{ formTitle }}</span>
                </v-card-title>
        
                <v-card-text>
                  <product-form :product="editedProduct" @updated="updateItems" @cancel="editedProduct=null; dialog=false;"/>
                </v-card-text>
              </v-card>
            </v-dialog>
          </v-toolbar>
        </template>
        <template v-slot:item.main_picture="{ item }">
          <div class="table-avatar" v-if="item.main_picture">
            <img :src="item.main_picture.url">
          </div>
        </template>
        <template v-slot:item.actions="{ item }">
          <v-btn icon color="gray" @click="editedProduct=item">
            <v-icon>edit</v-icon>
          </v-btn>
          <v-btn icon color="gray" @click="deleteItem(item)" :loading="item.deleting">
            <v-icon>delete</v-icon>
          </v-btn>
        </template>
      </v-data-table>
    </v-card>
  </div>
</template>

<script>
export default {
  props:['menu'],
  data(){return{
    search: '',
    headers:[
      {'text': 'Foto', 'alignment': 'center', 'sortable': false, 'value':'main_picture'},
      {'text': 'Producto', 'alignment': 'left', 'sortable': true, 'value':'name'},
      {'text': 'Descripción', 'alignment': 'left', 'sortable': false, 'value':'excerpt'},
      {'text': 'Precio', 'alignment': 'left', 'sortable': true, 'value':'price'},
      {'text': 'Acciones', 'alignment': 'right', 'sortable': false, 'value':'actions'},
    ],
    items: [],
    editedProduct: null,
    dialog: false
  }},
  methods:{
    deleteItem(item){
      if( window.confirm('Seguro que desea eliminar el producto?') ){
        axios.post(`/products/${item.id}`, {'_method':'DELETE'}).then(response=>{
          let index = this.items.indexOf(item)
          this.items.splice(index, 1)
          this.$toasted.show('Eliminado exitosamente')
        })
      }
    },
    updateItems(item){
      if(this.editedIndex >= 0){
        Object.assign(this.items[ this.editedIndex ], item)
      }else{
        this.items.push( item )
      }
      this.editedProduct = null
      this.dialog = false
    }
  },
  computed:{
    formTitle(){
      return this.editedIndex > 0 ? 'Actualizar producto' : 'Registrar producto'
    },
    editedIndex(){
      if(this.editedProduct){
        this.dialog = true
        return this.items.indexOf(this.editedProduct)
      }else{
        return -1
      }
    }
  },
  mounted(){
    this.items = [...this.menu]
  }
}
</script>

<style lang="scss" scoped>
.table-avatar {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 10px;
  img {
    height: 100%;
    width: auto;
  }
}
</style>
