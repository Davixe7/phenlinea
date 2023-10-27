<template>
  <div class="pic-item">
    <img :src="url" alt="" v-if="url">
    <button type="button" class="btn-remove" @click="$emit('removePic', pic)">&times;</button>
  </div>
</template>

<script>
export default {
  props: ['pic'],
  data(){return {
    url: ''
  }},
  mounted(){
    if( this.pic.url ){ this.url = this.pic.url; return }
    let that = this
    let fr = new FileReader()
    fr.onload = function(e){
      that.url = e.target.result
    }
    fr.readAsDataURL(this.pic)
  }
}
</script>

<style lang="scss" scoped>
  .pic-item {
    position: relative;
    width: 50px;
    height: 50px;
    border-radius: 10px;
    overflow: hidden;
    margin-right: 10px;
    img {
      height: 100%;
      width: auto;
    }
  }
  .pic-item:hover {
    img {
      transform: scale(1.1);
      filter: brightness(.5);
    }
  }
  .btn-remove {
    color: #fff;
    font-weight: 600;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    position: absolute;
    top: 0;
    right: 0;
    width: 20px;
    height: 20px;
    border: none;
    border-radius: 50%;
    background: #212121;
  }
</style>
