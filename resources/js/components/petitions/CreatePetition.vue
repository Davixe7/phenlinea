<template>
  <div id="create-petition">
    <div class="card">
      <div class="card-body">
        <form ref="createPetitionForm">
          
          <div class="row">
            <span class="col form-section-title">
              Descripción de la solicitud
            </span>
            <div class="col text-right">
              <span v-if="petition && petition.extension" class="mr-2" >Apto. #{{ petition.extension.name }}</span>
              <tag :status="status"/>
            </div>
          </div>
          <div class="form-group">
            <label for="title">Título</label>
            <input v-model="title" type="text" class="form-control" :class="{'is-invalid':errors.title && errors.title[0]}" minlength="5" required>
            <small class="form-text text-red" v-if="errors.title">{{ errors.title[0] }}</small>
          </div>
          <div class="form-group">
            <label for="body">Descripción</label>
            <textarea v-model="description" rows="3" class="form-control" :class="{'is-invalid':errors.description && errors.description[0]}" minlength="5" required></textarea>
            <small class="form-text text-red" v-if="errors.description">{{ errors.description[0] }}</small>
          </div>
          
          <span class="form-section-title">
            Información de contacto</span>
          <div class="row">
            <div class="form-group col">
              <label for="phone">Teléfono</label>
              <input v-model="phone" type="tel" class="form-control" :class="{'is-invalid':errors.phone && errors.phone[0]}" minlength="10" maxlength="10" required>
              <small class="form-text text-red" v-if="errors.phone">{{ errors.phone[0] }}</small>
            </div>
            
            <div class="form-group col">
              <label for="email">Email</label>
              <input v-model="email" type="email" class="form-control" :class="{'is-invalid':errors.email && errors.email[0]}" minlength="5" required>
              <small class="form-text text-red" v-if="errors.email">{{ errors.email[0] }}</small>
            </div>
          </div>
          
          <PicturesList v-if="petitionPictures.length" :pictures="petitionPictures" @deletePicture="deletePicture" @selected="( (index) => { lbindex = index } )"/>
          <MultipleFilesInput v-if="(!isAdmin && !editing)" :clear="clear" @filesUploaded="updateFiles"/>
          
          
          <div class="row py-4">
            <div class="col-md-6">
              <div class="form-row" v-if="isAdmin && editing && status == 'pending' ">
                <div class="col">
                  <button type="button" class="w-100 btn btn-success" @click="updateStatus('approved')">Aprobar</button>
                </div>
                <div class="col">
                  <button type="button" class="w-100 btn btn-danger" @click="updateStatus('denied')">Denegar</button>
                </div>
              </div>
            </div>
            <div class="col-md-6 text-right">
              <button v-if="!(isAdmin && !editing)" @click="unsetPetition" type="button" class="btn btn-link">Cancelar</button>
              <button v-if="!editing && !isAdmin"   @click="storePetition" type="button" class="btn btn-primary">Enviar</button>
            </div>
          </div>
          
          <div class="alert alert-info">
            <div class="row">
              <div class="col-md-1">
                <i class="material-icons">info</i> 
              </div>
              <div class="col-md-11">
                <span class="form-info">Las solicitudes tendran un estado inicial de <i>pendiente</i> y podran ser aprobadas o denegadas una vez revisadas por la administración.</span>
              </div>
            </div>
          </div>
        </form>
        
        <CoolLightBox :items="lbPictures" :index="lbindex" @close="lbindex=null"/>
      </div>
    </div>
    </div>
  </div>
</template>

<script>
import MultipleFilesInput from './../MultipleFilesInput.vue'
import PicturesList from './../PicturesList.vue'
import CoolLightBox from 'vue-cool-lightbox'
import Tag from './../Tag.vue'
export default {
  components: { Tag, MultipleFilesInput, PicturesList, CoolLightBox },
  name: 'CreatePetition',
  data(){ return {
    title: '',
    description: '',
    petitionPictures: [],
    pictures: [],
    phone: '',
    email: '',
    status: 'pending',
    editing: false,
    errors: {},
    clear: 0,
    lbindex: null
  }},
  computed:{
    lbPictures(){
      return this.petitionPictures.length ? this.petitionPictures.map( p => '/' + p.url ) : []
    }
  },
  props: {
    isAdmin: {type:Boolean, default: false},
    petition: { type: Object, default: ()=>{} } 
  },
  watch:{
    petition(newVal){
      if( newVal.id ){
        this.id = newVal.id
        this.title = newVal.title
        this.description = newVal.description
        this.phone  = newVal.phone
        this.email  = newVal.email
        this.status = newVal.status
        this.petitionPictures = newVal.pictures
        
        this.editing = true
        return true
      }
      this.clearForm()
    }
  },
  methods:{
    updateFiles(files){
      this.pictures = files
    },
    unsetPetition(){
      this.$emit('unsetPetition')
    },
    updateStatus(status){
      axios.post(`/petitions/${this.id}`,{status, _method:'PUT'}).then(response=>{
        this.status = status
        this.$toasted.success('Solicitud procesada exitosamente')
        this.$emit('petitionUpdated', response.data.data)
      },error=>{
        this.errors = error.response.data.errors
      })
    },
    storePetition(){
      if( this.$refs.createPetitionForm.reportValidity() ){
        let data = new FormData()
        data.append('title', this.title)
        data.append('description', this.description)
        data.append('phone', this.phone)
        data.append('email', this.email)
        
        this.pictures.forEach(pic=>{
          data.append('pictures[]', pic.file)
        })
        
        this.$emit('storePetition', data)
      }
    },
    deletePicture(pic){
      let data = {'_method':'PUT', 'picture':pic.path}
      axios.post(`/petitions/${this.petition.id}/deletepicture`, data).then(response=>{
        this.$emit('petitionUpdated', response.data.data)
      },error=>{
        console.log(error.response);
      })
    },
    clearForm(){
      this.pictures    = []
      this.petitionPictures    = []
      this.title       = ''
      this.description = ''
      this.phone       = ''
      this.email       = ''
      this.editing     = false
      this.status      = 'pending'
      this.clear       = Math.random(0,100)
    }
  }
}
</script>

<style lang="sass">
  .input-group-append input.form-control
    border-left: none
  .card-header
    display: flex
    flex-flow: row nowrap
    align-items: center
    padding: 15px
    h1
      margin: 0
  .form-section-title
    display: block
    font-size: .9em
    text-transform: uppercase
    color: gray
    margin-bottom: 10px
    
  .icon-wrapper
    text-align: center
    display: flex
    align-items: center
    justify-content: center
    width: 40px
    height: 40px
    margin-right: 10px
    border-radius: 50%
    background: #efefef
  
  .form-info
    font-size: .8em
    line-height: 1.25em
    color: gray
    display: block
    
  .tag
    border-radius: 5px
    padding: 5px 10px
    font-weight: 500
    font-size: .9em
    color: #fff
    background: gray
    
  .tag.tag-sm
    font-size: .8em
    padding: 2px 5px
    border-radius: 3px
  
  .btn-link.btn-sm
    i.material-icons
      font-size: 1.5em
</style>
