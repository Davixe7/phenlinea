<template>
  <div class="my-file-input">
    <input type="file" ref="FileInput" @change="loadFiles" id="fileinput" multiple>
  </div>
</template>

<script>
export default {
  data(){return{
    validTypes: ['image/jpeg', 'image/png', 'img/jpg']
  }},
  methods:{
    loadFiles(){
      let files = [...this.$refs.FileInput.files]
      files.filter(i => this.validTypes.includes( i.type ) )
      this.$emit('filesLoaded', files)
      this.$refs.FileInput.value = ''
    }
  }
}
</script>

<style lang="css" scoped>
</style>
