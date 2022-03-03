<template>
  <v-card outlined>
    <v-card-title>
      <i class="material-icons mr-3">
        receipt_long
      </i>
      Consultar Facturas
    </v-card-title>
    <v-card-text>
      <form ref="facturaForm">
        <v-autocomplete
          required
          v-model="admin"
          :items="admins"
          :search-input.sync="search"
          clearable
          filled
          item-text="name"
          item-value="id"
          label="Seleccione Unidad Residencial"
          return-object
        >
        </v-autocomplete>
        <v-text-field
          v-model="bill_code"
          required
          filled
          label="Código Unico de Apartamento">
        </v-text-field>
        <div v-if="errors.notFound" class="alert alert-danger">
          El número único de apartamento es invalido
        </div>
        <div class="text-right">
          <v-btn dark
            :loading="loading"
            @click="queryFactura"
            class="ml-auto">
            Consultar
          </v-btn>
        </div>
      </form>
    </v-card-text>
  </v-card>
</template>

<script>
export default {
  props: ['admins'],
  data(){return{
    bill_code: null,
    admin: null,
    loading: false,
    search: '',
    errors: {
      notFound: false
    }
  }},
  methods:{
    queryFactura(){
      if( !this.$refs.facturaForm.reportValidity() ){ return }
      this.loading = true
      this.errors.notFound = false
      let url = '/consultar-factura/?bill_code=' + this.bill_code
      axios.get(url).then(response => {
        window.location.href = "/ver-factura/" + response.data.data.id
      })
      .catch(error => {
        if( error.response.status == '404' ){
          this.errors.notFound = true
        }
      })
      .then(res => this.loading = false)
    }
  }
}
</script>

<style scoped>
.v-card {
  padding: 15px;
  border-radius: 8px;
  /* border: none !important;
  box-shadow: 0 1px 15px 2px rgba(0,0,0,.1); */
}
.v-card__title {
  color: #0a47e4 !important;
  font-size: 1.2em;
  font-weight: 500;
}
.v-card__title .material-icons {
  color: #0a47e4 !important;
}
</style>
