<template>
  <div id="novelties">
    <div class="overlay" v-show="noveltyToEdit"></div>
    <div class="row" v-show="!noveltyToEdit">
      <div class="col-md-8">
        <div class="page-title">
          <h1>Novedades</h1>
          <div v-if="novelties && novelties.length" class="ml-auto">
            <form autocomplete="off" onsubmit="e.preventDefault()">
              <input placeholder="buscar por nombre..." autocomplete="none" type="search" class="form-control md-control" v-model="searchText" @keyup="debouncedFilterNoveltys" name="new-password" id="new-password">
            </form>
          </div>
        </div>
      </div>
    </div>
    
    <div class="row" v-show="!noveltyToEdit">
      <div class="table-responsive col-md-8 novelties-table">
        <table class="table" v-if="novelties.length && results.length">
          <thead>
            <th>Fecha</th>
            <th>Descripción</th>
            <th class="text-right">Opciones</th>
          </thead>
          <tbody>
            <tr v-for="novelty in results" :class="{read: novelty.read}">
              <td>{{ novelty.created_at }}</td>
              <td>{{ novelty.excerpt }}</td>
              <td class="text-right">
                <div class="btn-group">
                  <a href="#" class="btn btn-sm btn-link" @click="viewNovelty(novelty)">
                    <i class="material-icons">remove_red_eye</i></a>
                </div> 
              </td>
            </tr>
          </tbody>
        </table>
        <div v-else class="alert alert-info">No hay Novedades para mostrar</div>
      </div>
    </div>
    
    <div class="row">
      <div class="col-md-8" v-show="noveltyToEdit">
        <novelty 
          v-if="noveltyToEdit"
          :novelty="noveltyToEdit"
          @resetNovelty="noveltyToEdit=null"
          />
      </div>
    </div>
    
  </div>
</template>

<script>
import Novelty from './Novelty.vue'
export default {
  name: 'Novelties',
  components: { Novelty },
  data(){
    return {
      noveltyToEdit: null,
      editing: false,
      novelties: [],
      searchText: '',
      results: []
    }
  },
  watch:{
    novelties(){
      this.results = _.cloneDeep( this.novelties )
    }
  },
  methods:{
    filterNoveltys(){
      if( !this.searchText ){
        this.results = this.novelties
        return
      }
      this.results = this.novelties.filter( p => p.title.toLowerCase().includes(this.searchText.toLowerCase()))
    },
    viewNovelty(n){
      this.noveltyToEdit = n
      this.editing = true
      n.read = 1
    }
  },
  mounted(){
    axios.get('/novelties/list').then((response)=>{
      this.novelties = response.data.data
    });
  },
  created(){
    this.debouncedFilterNoveltys = _.debounce( this.filterNoveltys, 500 )
  }
} 
</script>
<style>
  .overlay {
    position: absolute;
    height: 200px;
    width: 100%;
    left: 0;
    right: 0;
    top: 0;
    background: #36597b;
    z-index: 10;
  }
  .novelties-table tr td {
    color: #000;
    font-weight: 500;
  }
  .novelties-table tr.read td {
    color: gray;
    font-weight: 400;
  }
</style>