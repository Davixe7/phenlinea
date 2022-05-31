<template>
  <div id="resident-home">
    <h1>Cartelera</h1>
    <hr>
    <div class="row">
      <div class="col-md-6">
        <PostsList :posts="posts"/>
      </div>
    </div>
  </div>
</template>

<script>
import PostsList from './../posts/PostsList.vue'
export default {
  name: 'ResidentHome',
  components: { PostsList },
  data(){return {
    posts: []
  }},
  methods:{
    fetchPosts(){
      axios.get('posts/list').then(response=>{
        this.posts = response.data.data
      },error=>{
        console.log(error);
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
