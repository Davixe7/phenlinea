<template>
  <div class="pictures-list">
    <ul class="pictures">
      <li class="picture-wrapper" v-for="(p, i) in pictures" :key="p.path">
        <img :src="'/'+p.url" alt="">
        <div class="actions">
          <span class="delete zoom"  @click="$emit('selected', i)">
            <i class="material-icons">remove_red_eye</i>
          </span>
          <span class="delete" @click="deletePicture(p)">
            <i class="material-icons">delete</i>
          </span>
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  props:{
    'pictures': {
      type: Array,
      default:()=>{ [] } 
    }
  },
  name: 'PicturesList',
  data(){return{}},
  methods:{
    deletePicture(picture){
      if( confirm('¿Seguro que desea eliminar la imagen?') ){
        this.$emit('deletePicture', picture)
      }
    }
  }
}
</script>

<style lang="sass" scoped>
  .pictures
    display: flex
    flex-flow: row nowrap
    list-style-type: none
    padding: 0
    margin: 0 0 10px
    .picture-wrapper
      margin-right: 10px
      border-radius: 3px
      width: 50px
      height: 50px
      overflow: hidden
      position: relative
      .actions
        color: #fff
        font-weight: 500
        position: absolute
        top: 0
        right: 7px
        cursor: pointer
        display: inline-flex
        align-items: center
        background: rgba(0,0,0,.35)
        padding: 0 3px
        border-radius: 2px
      .actions span
        display: block
        cursor: pointer
        margin-right: 5px
        &:last-child
          margin-right: 0
        i.material-icons
          font-size: 1em
        
      img
        height: 100%
</style>
