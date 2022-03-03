<template>
  <div id="posts-list">
    <span class="form-section-title">Todos los documentos</span>
    <div v-if="posts && posts.length" class="posts-list">
      <div class="default-content" v-for="post in posts">
        <div class="content-header">
          <div>
            <h5 class="title">{{ post.title }}</h5>
            <span class="date">{{ post.created_at }}</span>
          </div>
          <div v-if="isAdmin" class="options-dropdown ml-auto">
            <div class="dropdown">
              <span class="dropdown-toggler" data-toggle="dropdown">
                <i class="material-icons">more_vert</i>
              </span>
              <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="#" @click.prevent="editPost(post)">Editar</a>
                <a class="dropdown-item" href="#" @click.prevent="deletePost(post)">Eliminar</a>
              </div>
            </div>
          </div>
        </div>
        <div class="description">
          {{ post.description }}
        </div>
        
        <div v-if="post.attachments && post.attachments.length" class="post-attachments">
          <small>Archivos adjuntos</small>
          <ul class="attachments-list">
            <li v-for="(file, i) in post.attachments">
              <a :href="file.url">
                #{{ i+1 }} Archivo adjunto
              </a>
            </li>
          </ul>
        </div>
        
      </div>
    </div>
    <div v-else class="alert alert-info">
      <i class="material-icons">info</i> No hay publicaciones para mostrar
    </div>
  </div>
</template>

<script>
export default {
  name: 'DocsList',
  props: {_posts: {type:Array, default: ()=>[] }, isAdmin:false},
  data(){
    return {
      index: null,
      pictures: [],
      posts: []
    }
  },
  watch:{
    _posts(newVal){
      if( newVal ){
        this.posts = newVal
      }
    }
  },
  computed:{
    lbPictures(){
      return this.pictures.map(p=>p.url)
    }
  },
  methods:{
    editPost(post){
      this.$emit('editPost', post)
    },
    deletePost(post){
      if( confirm('¿Seguro que desea eliminar la publicación?') ){
        this.$emit('deletePost', post)
      }
    },
    fecthPosts(){
      axios.get('/docs/list').then(response=>{
        this.posts = response.data.data
      },
      error=>{
        this.$toasted.error('Error al cargar las publicaciones')
      })
    }
  },
  mounted(){
    if( !(this._posts && this._posts.length) ){
      this.fecthPosts()
    }
  },
}
</script>

<style lang="sass" scoped>
  .posts-list
    display: flex
    flex-direction: column-reverse
    
  .pictures-list
    display: flex
    flex-flow: row nowrap
    margin-bottom: 10px
  .picture-wrapper
    width: 120px
    overflow: hidden
    margin-right: 10px
    border-radius: 3px
    box-shadow: 0 1px 3px 1px rgba(0,0,0,.2)
    img
      height: 130px
      width: auto
      
  .attachments-list
    display: flex
    flex-flow: row nowrap
    list-style-type: none
    padding: 0
    margin: 0 0 10px 0
    li
      margin-right: 10px
      padding: 5px 10px 7px
      background: #fff
      box-shadow: 0 1px 3px 1px rgba(0,0,0,.095)
      a 
        font-size: .9em
        font-weight: 500
      i.material-icons
        font-size: 1.35em
        margin-right: 5px
</style>
