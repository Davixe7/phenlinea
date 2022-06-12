<template>
  <div id="store-logo">
    <v-img :src="logoSRC" max-width="250"></v-img>
    <file-input @filesLoaded="(f)=>{newLogo=f[0]}"></file-input>
  </div>
</template>

<script>
export default {
  props: ['logo', 'id'],
  data(){return{
    newLogo: null,
    newLogoSRC: null,
    validTypes: ['image/jpeg','image/jpg','image/png']
  }},
  watch:{
    newLogo( newVal ){
      if( this.id && this.validTypes.includes( newVal.type ) ){
        if(!window.confirm('Desea remplazar el logo con la imagen subída?')) return
        this.uploadLogo()
      }
      else{
        this.$emit('input', oldVal)
      }
    }
  },
  computed:{
    logoSRC(){
      if(this.newLogoSRC) return this.newLogoSRC
      if(this.newLogo && !this.newLogo.url){
        this.parseFile()
      }
      if(!this.logo) return null
      if(this.logo.url) return this.logo.url
    }
  },
  methods:{
    uploadLogo(){
      this.loading = true
      let data = new FormData()
      data.append('logo',this.newLogo)
      data.append('_method', 'PUT')
      axios.post(`/stores/${this.id}`, data).then(response=>{
        console.log(response);
      },err=>{
        console.log(err.response);
      }).then(r=>{
        this.loading = false
      })
    },
    parseFile(){
      if(this.newLogo && this.validTypes.includes( this.newLogo.type )){
        let fr = new FileReader()
        let dis = this
        fr.onload = e => dis.newLogoSRC = e.target.result
        fr.readAsDataURL(this.newLogo)
      }
    }
  }
  
}
</script>

<style lang="scss" scoped>
</style>
