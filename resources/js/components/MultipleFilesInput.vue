<template>
  <div class="multiple-files-input" style="overflow: auto;">
    <input type="file" ref="fileinput" id="fileInput" accept="image/*" @input="uploadFile" multiple>
    
    <div class="pictures">
      <label @click="openFileDialog" class="file-input-loader" v-if="!onLimit && !disabled">+</label>
      <div class="picture-wrapper" v-for="(f,i) in files" :key="i">
        <span class="close" @click="removeFile(f)">&times;</span>
        <img v-if="f.src" :src="f.src" alt="">
      </div>
    </div>
    <span v-if="limit">Hasta {{ limit }} imagen(es)</span>
  </div>
</template>

<script>
export default {
  props: { clear: {type: Number, default: 0 }, limit: null, disabled: false},
  name: "MultipleFilesInput",
  data(){
    return {
      files: [],
    }
  },
  watch:{
    clear(newVal){
      this.files = []
    },
    files(newFiles){
      if( newFiles.length ){
        this.$emit('filesUploaded', this.files)
      }
    }
  },
  computed:{
    onLimit(){
      return this.limit && (this.files.length >= this.limit)
    }
  },
  methods:{
    openFileDialog(){
      this.$refs.fileinput.click()
    },
    removeFile(file){
      this.files = this.files.filter(f=>f!=file)
    },
    uploadFile(){
      let newFiles = [...this.$refs.fileinput.files]
      if( newFiles && newFiles.length ){
        newFiles.forEach( f => {
          let dat = this
          let reader = new FileReader()
          reader.onloadend = function(){
            if( dat.limit && (dat.files.length >= dat.limit) ){
              return
            }
            dat.files.push( { file: f, src: reader.result} )
          }
          reader.readAsDataURL( f )
        })
      }
      this.$refs.fileinput.value = ""
    }
  }
}
</script>

<style lang="sass" scoped>
  #fileInput
    display: none
  .file-input-loader
    display: inline-flex
    justify-content: center
    align-items: center
    color: lightgray
    font-size: 2em
    font-weight: 500
    text-align: center
    margin-right: 10px
    border: 1px dashed lightgray
  .file-input-loader, .picture-wrapper
    height: 120px
    width: 100px
    box-sizing: border-box
    border-radius: 3px
  .pictures
    display: flex
    flex-flow: row nowrap
    vertical-align: top
  .picture-wrapper
    display: inline-block
    position: relative
    margin: 0 10px 10px 0
    border-radius: 3px
    overflow: hidden
    box-shadow: 0 1px 3px 1px rgba(0,0,0,.2)
    img
      height: 100%
      width: auto
      max-width: none
    .close
      position: absolute
      cursor: pointer
      top: 3px
      right: 10px
      color: red
      text-shadow: 0 0 2px #000
      
  .pictures + span
    display: block
    font-weight: 500
    color: darkblue
    font-size: .9em
</style>
