<template>
  <div id="ads">
    <v-card>
      <v-card-title>
        Clasificados
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
              <v-icon>{{item.enabled != 'active' ? 'check_circle_outline' : 'block'}}</v-icon>
            </v-btn>
          </template>
          <span>{{item.enabled != 'active' ? 'Activar' : 'Suspender'}}</span>
        </v-tooltip>
        <v-btn icon color="gray" :href="'/admin/classifieds/' + item.id + '/edit'">
          <v-icon>edit</v-icon>
        </v-btn>
        <v-btn icon color="gray" @click="deleteAd(item)" :loading="item.deleting">
          <v-icon>delete</v-icon>
        </v-btn>
      </template>
      </v-data-table>
    </v-card>
    <v-btn
      href="/admin/classifieds/create"
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
  name: 'ads',
  props: ['ads'],
  data(){ return {
    updatedIndex: null,
    search: '',
    items: [],
    headers: [
      {text: 'ID', align: 'start', sortable: true, value:'id'},
      {text: 'Estado', align: 'start', sortable: true, value:'state'},
      {text: 'Ciudad', align: 'end', sortable: true, value:'city'},
      {text: 'Nombre', align: 'end', sortable: true, value:'name'},
      {text: 'Acción', align: 'end', sortable: false, value:'actions'},
    ],
  }},
  methods:{
    updateStatus(item){
      if( window.confirm('¿Seguro que desea cambiar el enabled del comercio?') ){
        item.updating = true
        this.updatedIndex = this.items.indexOf(item)
        axios.post(`/admin/classifieds/${item.id}/update-enabled`, {'_method':'PUT'}).then(response=>{
          this.$toasted.show('Actualizado exitosamente!', {position: 'bottom-center'})
          item.enabled = response.data.data.enabled
          item.updating = false
          Object.assign(this.items[this.updatedIndex],item)
        })
      }
    },
    deleteAd(item){
      if( window.confirm('¿Seguro que desea eliminar el clasificado?') ){
        item.deleting = true
        axios.post(`/admin/classifieds/${item.id}`, {'_method':'DELETE'}).then(response=>{
          this.$toasted.show('Eliminado exitosamente', {position: 'bottom-center'})
          this.items = this.items.filter(f=>f.id != item.id)
        })
      }
    }
  },
  mounted(){
    this.items = (this.ads && this.ads.length) ? [...this.ads] : []
  }
}
</script>

<style lang="css" scoped>
  .btn-add:hover, .btn-add:active {
    text-decoration: none;
  }
</style>
