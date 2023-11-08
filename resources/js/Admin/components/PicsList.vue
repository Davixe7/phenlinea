<template>
  <div class="pics-list-container">
    <div class="pics-list" v-if="pictures && pictures.length">
      <pic-item v-for="pic in pictures" :pic="pic" :key="pic.name" @removePic="deletePicture"/>
    </div>
  </div>
</template>

<script>
export default {
  props: ['pictures', 'endpoint'],
  data(){return{
    sources: []
  }},
  methods:{
    deletePicture(pic){
      if( this.endpoint ){
        axios.post(`${this.endpoint}/${pic.id}`, {'_method':'delete'}).then(response=>{
          this.$emit('removePic',pic)
          return true;
        })
      }else{
        this.$emit('removePic',pic)
      }
    }
  }
}
</script>

<style lang="scss" scoped>
  .pics-list {
    display: flex;
    overflow: auto;
  }
  .pic-item {
    max-width: 70px;
  }
  .pic-item img {
    height: 100px;
    width: auto;
  }
</style>
