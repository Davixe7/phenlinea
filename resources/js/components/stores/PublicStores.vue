<template>
  <div id="stores">
    <v-container>
      <v-card>
        <v-card-title>
          <v-row :align="'center'">
            <v-col cols="12" :lg="8">
              Buscar comercios
            </v-col>
            <v-col cols="12" :lg="4">
              <search-form :collection="byOpen" :attribute="'name'" v-model="results"/>
            </v-col>
          </v-row>
          <v-chip-group column active-class="dark--text light" v-model="categoryIndex" :next-icon="'keyboard_arrow_right'">
            <v-chip v-for="cat in categories" :key="cat" dark>
              {{ cat }}
            </v-chip>
          </v-chip-group>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
          <v-subheader>
            RESULTADOS
            <v-spacer></v-spacer>
            <v-switch v-model="checkopen" :label="'Abierto ahora'" class="v-input--reverse"></v-switch>
          </v-subheader>
          <v-list
            two-line
            dense
            shaped
            avatar
            rounded>
            <v-list-item-group color="primary">
              <v-list-item v-for="(item, i) in results" :key="i" :href="item.permalink" style="text-decoration: none !important;">
                <v-list-item-avatar v-if="item.logo">
                  <v-img :src="item.logo.url"/>
                </v-list-item-avatar>
                <v-list-item-content>
                  <v-list-item-title v-html="item.name"></v-list-item-title>
                  <v-list-item-subtitle v-html="item.description"></v-list-item-subtitle>
                </v-list-item-content>
                <v-list-item-action>
                  <v-btn icon><v-icon>arrow_right_alt</v-icon></v-btn>
                </v-list-item-action>
              </v-list-item>
            </v-list-item-group>
          </v-list>
        </v-card-text>
      </v-card>
    </v-container>
  </div>
</template>

<script>
export default {
  props: ['stores'],
  data(){ return {
    byCategory: [],
    byOpen: [],
    results: [],
    categoryIndex: null,
    checkopen: false,
    categories: [
      'Comidas',
      'Carnicerías',
      'Legumbrerias',
      'Supermercados',
      'Licores',
      'Tiendas',
      'Ferreterías',
      'Otros'
    ],
  }},
  computed:{
    category(){
      return this.categories[this.categoryIndex]
    }
  },
  watch:{
    checkopen(newVal){
      this.filterByOpen(newVal)
    },
    category(newVal, oldVal){
      if(newVal && (newVal != 'todas')){
        this.byCategory = this.stores.filter(r => r.category == newVal )
      }else{
        this.byCategory = [...this.stores]
      }
      this.filterByOpen(this.checkopen)
    }
  },
  methods:{
    filterByOpen(checkopen){
      if( checkopen ){
        this.byOpen = this.byCategory.filter(r => this.isOpen( r ))
        return
      }
      this.byOpen = [...this.byCategory]
    },
    isOpen(store){
      let d = new Date()
      let utc = d.getTime() + ( d.getTimezoneOffset() * 60000 )
      let co  = new Date(utc + ( -5 * 3600000 ))
      let dayTimeMinutes = (co.getHours() * 60) + (co.getMinutes())
      
      let wk = store.schedule[ co.getDay() - 1 ]
      return (dayTimeMinutes > wk.open) && (dayTimeMinutes < wk.close)
    }
  },
  mounted(){
    this.byCategory = [...this.stores]
    this.results = [...this.stores]
  }
}
</script>

<style lang="scss">
  .v-input--reverse {
    flex-direction: row-reverse;
  }
  label {
    margin-bottom: 0 !important;
  }
</style>
