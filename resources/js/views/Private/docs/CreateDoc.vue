<template>
  <div id="create-post" ref="formContainer">
    
  <div class="card">
    <div class="card-body">
      <span class="form-section-title">Registrar Manual / Documento</span>
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
        
        <span class="pill" :class="{selected:attaching=='files'}" @click="attaching='files'">
          <i class="material-icons">attach_file</i> 
          Adjuntar archivos
        </span>
        
        <div class="form-group file-input-container">
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
      
      // title: 'Lorem ipsum',
      // description: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cupiditate harum magnam quae exercitationem culpa labore deserunt voluptatem, dolore veritatis, ducimus fuga nisi error animi, voluptatum suscipit sit laborum. A, vero!',
      
      title: '',
      description: '',
      
      postAttachments: [],
      
      clear: 0,
      errors: {},
    }},
    watch:{
      post(newVal){
        if( newVal && newVal.id ){
          this.title = newVal.title
          this.description = newVal.description
          this.postAttachments = newVal.attachments
          this.editing = true
          return true
        }
        this.clearForm()
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
      storePost(){
        if( this.$refs.createPostForm.reportValidity() ){
          let data = this.loadData()
          axios.post('/docs', data).then(response=>{
            this.$toasted.success('El manual/doc se realizó exitosamente')
            this.$emit('postCreated', response.data.data)
            this.clearForm()
          },error => {
            this.$toasted.error('El manual/doc no pudo ser realizada')
            this.errors = error.response.data.errors
          }).then( ()=>{ this.isLoading = false } )
        }
      },
      updatePost(){
        if( this.$refs.createPostForm.reportValidity() ){
          let data = this.loadData()
          data.append('_method', 'PUT')
          axios.post(`/posts/${this.post.id}`, data).then(response=>{
            this.$toasted.success('El manual/doc se actualizó exitosamente')
            this.$emit('postUpdated', response.data.data, false)
            this.clearForm()
          },error => {
            this.$toasted.error('El manual/doc no pudo ser actualizado')
            this.errors = error.response.data.errors
          }).then( ()=>{ this.isLoading = false } )
        }
      },
      loadData(){
        this.isLoading = true
        let data = new FormData()
        data.append('title', this.title)
        data.append('description', this.description)
        let attachments = [...this.$refs.attachmentsInput.files]
        attachments.forEach(file => {
          data.append('attachments[]', file)
        })
        return data
      },
      clearForm(){
        this.title            = ''
        this.description      = ''
        this.postAttachments  = []
        this.editing          = false
        this.isLoading        = false
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
        background: #fff
        box-shadow: 0 1px 3px 1px rgba(0,0,0,.095)
      a 
        font-size: .9em
        font-weight: 500
      i.material-icons
        font-size: 1.35em
        margin-right: 5px
  </style>
