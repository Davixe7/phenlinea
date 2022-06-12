<template>
  <div id="novelty" class="novelty">
    <div class="title">
      <h1>Novedad #{{ novelty.id }}</h1>
      <span class="date">
        <i class="material-icons">date_range</i> {{ novelty.created_at }}
      </span>
    </div>
    <div class="card card-body z-10">
      <div class="content">
        {{ novelty.description }}
      </div>
      <div class="subtitle">
        <i class="material-icons">camera_alt</i> Imágenes adjuntas
      </div>
      <div class="row">
        <div v-for="(picture, _index) in pictures" class="col-md-4">
          <img v-if="picture" :src="picture" @click="index=_index">
        </div>
      </div>
      <a href="#" class="btn btn-link px-0 my-3" @click="resetNovelty">
        <i class="material-icons">keyboard_arrow_left</i>
        Volver al listado
      </a>
      <CoolLightBox :items="pictures" :index="index" @close="index=null"/>
    </div>
  </div>
</template>

<script>
import CoolLightBox from 'vue-cool-lightbox'
export default {
  name: 'Novelty',
  components: { CoolLightBox },
  props: ['novelty'],
  data(){
    return {
      index: null
    }
  },
  computed:{
    pictures(){
      if( this.novelty.pictures ){
        return this.novelty.pictures.map( p => p.url )
      }
      return []
    }
  },
  watch:{},
  methods:{
    resetNovelty(){
      this.$emit('resetNovelty')
    }
  },
  mounted(){
    axios.post(`/novelties/${this.novelty.id}/markasread`, {'_method':'PUT'}).then(response=>{
      console.log('marcado como leido');
    })
  }
}
</script>

<style lang="css" scoped>
  .subtitle {
    font-size: .9em;
    color: gray;
    display: block;
    margin-bottom: 10px;
    border-bottom: 1px solid gray;
  }
  .novelty {
    
  }
  .novelty .title {
    position: relative;
    z-index: 200;
    color: #fff;
    padding: 15px;
  }
  .novelty .date i.material-icons {
    font-size: 1.2em;
    margin-right: 2.5px;
  }
  .novelty .date {
    display: block;
    font-size: .9em;
    color: #fff;
    margin-bottom: 10px;
    font-weight: 500;
  }
  .novelty .content {
    padding-bottom: 20px;
    font-size: 16px;
  }
  .z-10 {
    border: none;
    border-radius: 4px;
    box-shadow: 0 3px 18px 1px rgba(0,0,0,.2);
    position: relative;
    z-index: 100;
    padding: 20px 30px;
  }
</style>
