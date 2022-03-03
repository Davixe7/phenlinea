<template>
  <div id="posts">
    <div class="row">
      <div class="col-md-6 mx-auto">
        <h1>Manuales &amp; Documentos</h1>
        <hr>
        <DocsList v-if="posts" :_posts="posts"/>
      </div>
    </div>
  </div>
</template>

<script>
import DocsList  from './DocsList.vue'

export default {
  components: { DocsList },
  name: 'ExtensionPosts',
  data(){ return {
    posts: [],
    currentPost: {}
  }},
  methods:{
    fetchPosts(){
      axios.get('/docs/list').then(response=>{
        this.posts = response.data.data
      },
      error=>{
        this.$toasted.error('Error al cargar los documentos')
      })
    }
  },
  mounted(){
    this.fetchPosts()
  }
}
</script>

<style lang="css" scoped>
</style>
