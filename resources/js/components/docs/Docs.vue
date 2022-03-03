<template>
  <div id="docs">
    <h1>Manuales &amp; Documentos</h1>
    <hr>
    <div class="row">
      <div class="col-md-5">
        <CreateDoc :post="currentPost" @postCreated="pushPost" @postUpdated="refreshPost" @postUnset="currentPost={}"/>
      </div>
      <div class="col-md-6 offset-md-1">
        <DocsList v-if="posts" :isAdmin="true" :_posts="posts" @deletePost="deletePost" @editPost="editPost"/>
      </div>
    </div>
  </div>
</template>

<script>
import CreateDoc from './CreateDoc.vue'
import DocsList  from './DocsList.vue'

export default {
  components: { CreateDoc, DocsList },
  name: 'Docs',
  data(){ return {
    posts: [],
    currentPost: {}
  }},
  methods:{
    editPost(post){
      this.currentPost = {...post}
    },
    pushPost(post){
      this.posts.push(post)
    },
    refreshPost(post, keepCurrent = true){
      this.posts = this.posts.map( p => post.id == p.id ? post : p )
      if( keepCurrent ){
        this.currentPost = post
      }
    },
    deletePost(post){
      axios.delete(`docs/${post.id}`).then(response=>{
        this.posts = this.posts.filter(p=> p.id != post.id )
        this.currentPost = {}
        this.$toasted.success('Publicación eliminada exitosamente')
      },error=>{
        console.log(error.response.data);
      })
    },
    fetchPosts(){
      axios.get('/docs/list').then(response=>{
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
