<template>
  <div id="sms-log">
    <v-card>
    <v-card-title>
      Historial de SMS
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
    ></v-data-table>
  </v-card>
  </div>
</template>

<script>
export default {
  props: ['log'],
  name: 'SmsLog',
  data(){return{
    search: '',
    headers: [
      {text: 'Enviado el', align: 'start', sortable: true, value:'date'},
      {text: 'Extension', align: 'center', sortable: true, value:'extension_id'},
      {text: 'Tipo', align: 'end', sortable: true, value:'type'},
    ],
    types: {
      'delivery': 'encomienda',
      'admin':    'administracion',
      'services': 'servicios'
    }
  }},
  computed:{
    items(){
      if(this.log && this.log.length){
        return this.log.map(sms=>{
          return {
            date: sms.date,
            extension_id: (sms.extension_id && sms.extension) ? sms.extension.name : 'TODOS',
            type: this.types[ sms.type ]
          }
        })
      }
      return []
    }
  }
}
</script>

<style lang="css" scoped>
  h1 {
    margin-bottom: 10px;
  }
</style>
