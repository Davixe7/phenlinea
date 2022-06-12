<template>
  <div id="create-post" ref="formContainer">
    
  <div class="card">
    <div class="card-body">
      <span class="form-section-title">Crear publicación</span>
      <form ref="createPostForm">
        <div class="form-group">
          <label for="title">Título</label>
          <input v-model="title" type="text" class="form-control" :class="{'is-invalid':errors.title && errors.title[0]}" minlength="5" required>
          <small class="form-text text-red" v-if="errors.title">{{ errors.title[0] }}</small>
        </div>
        
        <div class="form-group">
          <label for="body">Descripcion</label>
          <textarea v-model="description" rows="5" class="form-control" :class="{'is-invalid':errors.description && errors.description[0]}" minlength="5" required></textarea>
          <small class="form-text text-red" v-if="errors.description">{{ errors.description[0] }}</small>
        </div>
        
        <span class="pill" :class="{selected:attaching=='pictures'}" @click="attaching='pictures'">
          <i class="material-icons">photo</i> 
          Adjuntar imagenes
        </span>
        <span class="pill" :class="{selected:attaching=='files'}" @click="attaching='files'">
          <i class="material-icons">attach_file</i> 
          Adjuntar archivos
        </span>
        
        <div v-show="attaching=='pictures'" class="form-group">
          <div v-if="postPictures && postPictures.length">
            <label>Imagenes actuales</label>
            <PicturesList :pictures="postPictures" @deletePicture="deletePicture"/>
          </div>
          <label>Subir imagenes</label>
          <MultipleFilesInput @filesUploaded="updateFiles" :clear="clear"/>
        </div>
        
        <div v-show="attaching=='files'" class="form-group file-input-container">
          <div v-if="postAttachments && postAttachments.length" class="post-attachments">
            <label>Archivos adjuntos</label>
            <ul class="attachments-list">
              <li v-for="(file, i) in postAttachments">
                <span class="delete" @click="deleteAttachment(file)">&times;</span>
                <a :href="file.url">#{{ i+1 }} {{ file.name }}</a>
              </li>
            </ul>
          </div>
          <label>Adjuntar archivos</label>
          <input type="file" ref="attachmentsInput" multiple>
        </div>
        
        <ul v-if="fileErrors && fileErrors.length">
          <li v-for="err in fileErrors">
            <small class="text-red">{{ err[0] }}</small>
          </li>
        </ul>
        
        <div class="text-right">
          <button @click="clearForm" type="button" class="btn btn-link">Cancelar</button>
          <button v-show="!editing" @click="storePost" type="button" class="btn btn-primary">Guardar</button>
          <button v-show="editing" @click="updatePost" type="button" class="btn btn-primary">Actualizar</button>
        </div>
      </form>
    </div>
  </div>
  
  <loading :active.sync="isLoading" :container="loadingParent" :is-full-page="false"></loading>
  </div>
</template>

<script>
  import Loading from 'vue-loading-overlay';

  export default {
    components: {Loading},
    props: { post: {type: Object, default: ()=>{} } },
    name: 'CreatePost',
    data(){ return {
      attaching: 'files',
      editing: false,
      isLoading: false,
      loadingParent: null,
      
      title: 'Lorem ipsum',
      description: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cupiditate harum magnam quae exercitationem culpa labore deserunt voluptatem, dolore veritatis, ducimus fuga nisi error animi, voluptatum suscipit sit laborum. A, vero!',
      pictures: [],
      postPictures: [],
      postAttachments: [],
      
      clear: 0,
      errors: {},
    }},
    watch:{
      post(newVal){
        if( newVal && newVal.id ){
          this.title = newVal.title
          this.description = newVal.description
          this.postPictures = newVal.pictures
          this.postAttachments = newVal.attachments
          this.editing = true
          return true
        }
        this.clearForm()
      }
    },
    computed:{
      fileErrors(){
        let keys = Object.keys(this.errors).filter( k => k.includes('pictures') )
        let response = _.pick( this.errors, keys )
        return _.mapKeys(response, function(val,key){
          return key.replace('pictures.', '')
        })
      }
    },
    methods:{
      deleteAttachment(file){
        if( confirm('¿Seguro que desea eliminar el archivo?') ){
          this.isLoading = true
          axios.post(`/posts/${this.post.id}/deleteattachment`, {_method:'PUT', attachment:file.path})
          .then(response=>{
            this.$toasted.success('Archivo eliminado exitosamente')
            this.$emit('postUpdated', response.data.data)
          })
          .then(()=>{
            this.isLoading = false
          })
        }
      },
      deletePicture(picture){
        this.isLoading = true
        axios.post(`/posts/${this.post.id}/deletepicture`, {_method:'PUT', picture:picture.path})
        .then(response=>{
          this.$toasted.success('Imágen eliminada exitosamente')
          this.$emit('postUpdated', response.data.data)
        })
        .then(()=>this.isLoading = false)
      },
      updateFiles(files){
        this.pictures = files
      },
      storePost(){
        if( this.$refs.createPostForm.reportValidity() ){
          let data = this.loadData()
          axios.post('/posts', data).then(response=>{
            this.$toasted.success('La publicación se realizó exitosamente')
            this.$emit('postCreated', response.data.data)
            this.clearForm()
          },error => {
            this.$toasted.error('La publicación no pudo ser realizada')
            this.errors = error.response.data.errors
          }).then( ()=>{ this.isLoading = false } )
        }
      },
      updatePost(){
        if( this.$refs.createPostForm.reportValidity() ){
          let data = this.loadData()
          data.append('_method', 'PUT')
          axios.post(`/posts/${this.post.id}`, data).then(response=>{
            this.$toasted.success('La publicación se actualizó exitosamente')
            this.$emit('postUpdated', response.data.data, false)
            this.clearForm()
          },error => {
            this.$toasted.error('La publicación no pudo ser actualizada')
            this.errors = error.response.data.errors
          }).then( ()=>{ this.isLoading = false } )
        }
      },
      loadData(){
        this.isLoading = true
        let data = new FormData()
        data.append('title', this.title)
        data.append('description', this.description)
        this.pictures.forEach(pic => {
          data.append('pictures[]', pic.file)
        })
        let attachments = [...this.$refs.attachmentsInput.files]
        attachments.forEach(file => {
          data.append('attachments[]', file)
        })
        return data
      },
      clearForm(){
        this.title            = ''
        this.description      = ''
        this.pictures         = []
        this.postPictures     = []
        this.postAttachments  = []
        this.editing          = false
        this.isLoading        = false
        this.clear            = Math.random(0,100)
        this.$refs.attachmentsInput.value = null
      },
      cancelEdition(){
        this.$emit('postUnset')
      }
    },
    mounted(){
      this.loadingParent = document.querySelector('#create-post')
    }
  }
</script>

<style lang="sass" scoped>
  .file-input-container
    border: 1px solid rgb(207, 207, 255)
    background: rgb(228, 228, 228)
    padding: 10px
    border-radius: 3px

  #create-post
    position: relative
    .form-group label
      display: none  
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
    span.delete
      color: #fff
      font-weight: 500
      position: absolute
      top: 0
      right: 7px
      cursor: pointer
    img
      height: 100%
    .attachments-list
      display: flex
      flex-flow: row nowrap
      list-style-type: none
      padding: 0
      margin: 0 0 10px 0
    li
      margin-right: 10px
      padding: 5px 10px 7px
      border: 1px solid #efefef
    a 
      font-size: .9em
    i.material-icons
      font-size: 1.35em
      margin-right: 5px
  </style>
