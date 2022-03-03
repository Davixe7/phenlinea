<template>
  <div id="posts">
    <div class="row">
      <div class="col-md-6 mx-auto">
        <h1>Cartelera</h1>
        <hr>
        <PostsList v-if="posts" :_posts="posts"/>
      </div>
    </div>
  </div>
</template>

<script>
import PostsList  from './PostsList.vue'

export default {
  components: { PostsList },
  name: 'ExtensionPosts',
  data(){ return {
    posts: [],
    currentPost: {}
  }},
  methods:{
    fetchPosts(){
      axios.get('/posts/list').then(response=>{
        this.posts = response.data.data
      },
      error=>{
        this.$toasted.error('Error al cargar las publicaciones')
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
