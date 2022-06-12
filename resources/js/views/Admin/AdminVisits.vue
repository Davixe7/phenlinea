<template>
  <div id="visits-table">
    <v-card>
        <v-card-title>
            Visitas
            <v-spacer/>
            <v-text-field
            class="p-0 m-0"
            v-model="search"
            append-icon="search"
            label="Buscar..."
            single-line
            hide-details
            />
        </v-card-title>
        <v-data-table
        :headers="headers"
        :items="visits"
        :search="search"
      >
        <template v-slot:item.apartment="{ item }">
          <div>
              {{ item.extension.name }}
          </div>
        </template>
        <template v-slot:item.picture="{ item }">
          <div class="table-avatar" v-if="item.picture">
            <img :src="item.picture" style="width: 40px; border-radius: 50%;">
          </div>
        </template>
      </v-data-table>
    </v-card>
  </div>
</template>

<script>
export default {
  props: ['visits'],
  name: 'AdminVisits',
  data(){return{
    pictureDialog: false,
    loading: true,
    search: '',
    picture: '',
    headers: [
      {
        value: 'picture',
        text: 'Foto',
        align: 'left',
        sortable: false
      },
      {
        value: 'apartment',
        text: 'APTO',
        align: 'left',
      },
      {
        value: 'dni',
        text: 'Cédula',
        align: 'left'
      },
      {
        value: 'name',
        text: 'Nombre',
        align: 'left'
      },
      {
        value: 'plate',
        text: 'Placa',
        align: 'left'
      },
      {
        value: 'company',
        text: 'Empresa',
        align: 'left'
      },
      {
        value: 'arl_eps',
        text: 'ARL-EPS',
        align: 'left'
      },
      {
        value: 'checkin',
        text: 'Entrada',
        align: 'left',
        sortable: true
      },
      {
        value: 'checkout',
        text: 'Salida',
        align: 'left',
        sortable: true
      },
    ],
  }},
  methods:{
    setPicture(visit){
      if( visit.picture ){
        this.pictureDialog = true
        this.picture = visit.picture
      }
    }
  }
}
</script>

<style lang="scss">
    .table-avatar img {
        border-radius: 50%;
        width: 40px;
    }
</style>
