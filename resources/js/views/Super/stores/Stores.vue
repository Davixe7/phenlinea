<template>
  <div id="stores">
    <v-card>
      <v-card-title>
        Comercios
        <v-spacer></v-spacer>
        <v-text-field
        class="p-0 m-0"
        v-model="search"
        append-icon="search"
        label="Buscar..."
        single-line
        hide-details
        ></v-text-field>
      </v-card-title>
      <v-data-table
        :headers="headers"
        :items="items"
        :search="search"
      >
      <template v-slot:item.actions="{ item }">
        <v-tooltip top>
          <template v-slot:activator="{on, attrs}">
            <v-btn icon color="gray" @click="updateStatus(item)" :loading="item.updating" v-bind="attrs"v-on="on">
              <v-icon>{{item.status != 'active' ? 'check_circle_outline' : 'block'}}</v-icon>
            </v-btn>
          </template>
          <span>{{item.status != 'active' ? 'Activar' : 'Suspender'}}</span>
        </v-tooltip>
        <v-btn icon color="gray" :href="'/admin/stores/' + item.id + '/edit'">
          <v-icon>edit</v-icon>
        </v-btn>
        <v-btn icon color="gray" @click="deleteStore(item)" :loading="item.deleting">
          <v-icon>delete</v-icon>
        </v-btn>
      </template>
      </v-data-table>
    </v-card>
    <v-btn
      href="/admin/stores/create"
      color="primary" dark
      bottom
      right
      fixed
      fab>
      <v-icon>add</v-icon>
    </v-btn>
  </div>
</template>

<script>

export default {
  name: 'Stores',
  props: ['stores'],
  data(){ return {
    updatedIndex: null,
    search: '',
    items: [],
    headers: [
      {text: 'Categoría', align: 'start', sortable: true, value:'category'},
      {text: 'Nombre', align: 'start', sortable: true, value:'name'},
      {text: 'Teléfono', align: 'end', sortable: true, value:'phone_1'},
      {text: 'Acción', align: 'end', sortable: false, value:'actions'},
    ],
  }},
  methods:{
    updateStatus(item){
      if( window.confirm('¿Seguro que desea cambiar el status del comercio?') ){
        item.updating = true
        this.updatedIndex = this.items.indexOf(item)
        axios.post(`/admin/stores/${item.id}/update-status`, {'_method':'PUT'}).then(response=>{
          this.$toasted.show('Actualizado exitosamente!', {position: 'bottom-center'})
          item.status = response.data.data.status
          item.updating = false
          Object.assign(this.items[this.updatedIndex],item)
        })
      }
    },
    deleteStore(item){
      if( window.confirm('¿Seguro que desea eliminar el comercio?') ){
        item.deleting = true
        axios.post(`/admin/stores/${item.id}`, {'_method':'DELETE'}).then(response=>{
          this.$toasted.show('Eliminado exitosamente', {position: 'bottom-center'})
          this.items = this.items.filter(f=>f.id != item.id)
        })
      }
    }
  },
  mounted(){
    this.items = (this.stores && this.stores.length) ? [...this.stores] : []
  }
}
</script>

<style lang="css" scoped>
  .btn-add:hover, .btn-add:active {
    text-decoration: none;
  }
</style>
