<template>
  <div id="craiglist">
    <v-toolbar class="mb-3" color="yellow">
      <v-toolbar-title class="mr-4">Clasificados</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-text-field filled dense hide-details clearable
        v-model="query"
        prepend-inner-icon="search"
        placeholder="Buscar por nombre..."/>
      <v-spacer></v-spacer>
    </v-toolbar>
    
    <div class="row px-4">
      <div class="col-md-3 aside-filters">
        <div class="card">
          <div class="card-body">
            <span class="form-section-title">
              Filtrar por ubicación
            </span>
            <v-select v-model="state"
              return-object
              hide-details
              :items="locations"
              item-text="departamento"
              :label="'Departamento'"
              :placeholder="'Seleccionar estado'"
              :clearable="true">
            </v-select>
            
            <v-select v-model="city"
              :disabled="!state"
              :items="cities"
              :placeholder="'Seleccionar ciudad'">
            </v-select>
            
            <v-btn dark
              v-text="'Buscar'"
              class="w-100"
              @click="fetchAds(1)"
              :loading="fetching"/>
          </div>
        </div>
      </div>
      
      <div class="col-md-9">
        <div class="row" v-if="ads && ads.length">
          <ad-content
            v-for="ad in ads"
            :ad="ad"
            :id="ad.id"
            :key="ad.id"
            class="col-md-4"/>
        </div>
        
        <div v-else class="alert alert-info d-flex align-items-center">
          <i class="material-icons mr-3">info</i> 
          No hay clasificados para mostrar
        </div>
      </div>
    </div>
    
  </div>
</template>

<script>
export default {
  props: ['locations'],
  data(){ return {
      query:'',
      ads: [],
      state: null,
      city: '',
      paginationData: {},
      fetching: false
  }},
  watch:{
    state(newVal){
      if(!newVal){ this.city = null }
    }
  },
  computed:{
    cities(){
      return this.state && this.state.ciudades ? this.state.ciudades : []
    },
  },
  methods:{
    fetchAds(){
      if( !this.fetching ){
        this.fetching = true
        let dept = this.state && this.state.departamento ? this.state.departamento : null
        let url = '/clasificados/'
        url = (dept) ? url + '?state=' + dept : url
        url = (this.city)  ? url + '&city=' + this.city : url
        
        axios.get(url, {params:{'custom_url':url}}).then(response=>{
          this.paginationData = response.data
          this.ads = response.data.data
          this.fetching = false
        },err=>{})
      }
    }
  },
  mounted(){
    if( this.$attrs.ads ){
      this.ads = [...this.$attrs.ads]
    }
  }
}
</script>

<style lang="scss" scoped>
  .aside-filters {
    position: sticky;
    top: 10px;
  }
</style>
