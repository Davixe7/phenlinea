<template>
  <div id="create-post" ref="formContainer">
  
  <div class="row">
    <div class="col-md-3">
      <div class="card mb-3">
        <div class="card-body" v-if="state">
          <span class="form-section-title">
            Ubicación del artículo o servicio
          </span>
          
          <v-select v-model="state"
            return-object
            :items="locations"
            item-text="departamento"
            :label="'Departamento'"
            :placeholder="'Seleccionar estado'">
          </v-select>
          
          <v-select v-model="city"
            :items="state.ciudades"
            :label="'Ciudad'"
            :placeholder="'Seleccionar ciudad'">
          </v-select>
        </div>
      </div>
      <div class="card">
        <div class="card-body">
          <span class="form-section-title">
            Información de contacto
          </span>
            
          <v-text-field outlined dense
            required
            label="Teléfono 1"
            v-model="phone_1"/>
            
          <v-text-field outlined dense
            required
            label="Teléfono 2"
            v-model="phone_2"/>
            
          <v-text-field outlined dense
            required
            label="Email"
            v-model="email"/>
        </div>
      </div>
    </div>
    <div class="col-md-9">
      <span class="form-section-title">Detalles del clasificado</span>
      <form ref="createPostForm">
        <div class="form-group">
          <label for="name">Título</label>
          <input v-model="name" type="text" class="form-control" placeholder="Título del clasificado" :class="{'is-invalid':errors.name && errors.name[0]}" minlength="5" required>
          <small class="form-text text-red" v-if="errors.name">{{ errors.name[0] }}</small>
        </div>
        
        <div class="form-group">
          <label for="body">Descripcion</label>
          <textarea v-model="description" rows="5" class="form-control" placeholder="Descripción" :class="{'is-invalid':errors.description && errors.description[0]}" minlength="5" required></textarea>
          <small class="form-text text-red" v-if="errors.description">{{ errors.description[0] }}</small>
        </div>
        
        <v-chip dark>
          <v-icon>photo</v-icon>
          Adjuntar imagenes
        </v-chip>
        
        <div class="form-group">
          <div v-if="ad && ad.id && postPictures" class="my-3">
            <label>Imagenes actuales</label>
            <pics-list :pictures="postPictures" :endpoint="'/attachments'"/>
            <v-divider></v-divider>
          </div>
          <pics-list :pictures="pictures" @removePic="removePic" class="my-3"/>
          <file-input v-model="pictures" @filesLoaded="p=>pictures=[...p]"/>
        </div>
        
        <div class="text-right">
          <v-btn text @click="clearForm">Cancelar</v-btn>
          <v-btn v-show="!editing" :loading="saving" @click="storePost" dark>Guardar</v-btn>
          <v-btn v-show="editing"  :loading="saving" @click="updatePost" dark>Actualizar</v-btn>
        </div>
      </form>
    </div>
  </div>
  
  </div>
</template>

<script>
  export default {
    props: { 
      ad: {type: Object, default: ()=>{} },
      locations: {type: Array, default: ()=>[] },
    },
    data(){ return {
      name: 'Lorem ipsum',
      description: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cupiditate harum magnam quae exercitationem culpa labore deserunt voluptatem, dolore veritatis, ducimus fuga nisi error animi, voluptatum suscipit sit laborum. A, vero!',
      price: 100,
      email: '',
      phone_1: '',
      phone_2: '',
      state: {},
      city: {},
      address: '',
      pictures: [],
      
      postPictures: [],
      errors: {},
      saving: false,
    }},
    computed:{
      editing(){
        return this.ad && this.ad.id ? true : false
      },
      states(){
        return this.locations && this.locations.length ? this.locations : []
      }
    },
    methods:{
      removePic(pic){
        let picIndex = this.pictures.indexOf(pic)
        this.pictures.splice( picIndex, 1 )
      },
      storePost(){
        if( this.$refs.createPostForm.reportValidity() ){
          axios.post('/admin/classifieds', this.loadData()).then(response=>{
            this.$toasted.success('Clasificado registrado exitosamente')
            this.$emit('postCreated', response.data.data)
            this.clearForm()
          },error => {
            this.$toasted.error('No se pudo registrar')
            this.errors = error.response.data.errors
          }).then( ()=>{ this.saving = false } )
        }
      },
      updatePost(){
        if( this.$refs.createPostForm.reportValidity() ){
          let data = this.loadData()
          data.append('_method', 'PUT')
          axios.post(`/admin/classifieds/${this.ad.id}`, data).then(response=>{
            this.$toasted.success('Clasificado actualizado exitosamente')
            this.setAd(response.data.data)
          },error => {
            this.$toasted.error('No se pudo actualizar el clasificado')
            this.errors = error.response.data.errors
          }).then( ()=>{ this.saving = false } )
        }
      },
      loadData(){
        this.saving = true
        let data = new FormData()
        let attributes = ['name','description','price','city','address','phone_1','phone_2','email']
        attributes.forEach(att=>data.append(att, this[att]))
        data.append('state', this.state.departamento)
        this.pictures.forEach(pic => data.append('pictures[]', pic))
        return data
      },
      setAd(ad){
        if( this.editing ){
          let attributes = ['name','description','price','state','city','address','phone_1','phone_2','email']
          attributes.forEach(att => this[att] = ad[att])
        }
        this.postPictures = Object.values(ad.pictures)
        this.state = ad.id ? this.states.filter( f => f.departamento == ad.state)[0] : this.states[0]
      },
      clearForm(){
        let attributes = ['name','description','address','email','phone_1','phone_2','city']
        attributes.forEach(att => this[att] = '')
        this.pictures      = []
        this.postPictures  = []
        this.saving        = false
      }
    },
    mounted(){
      this.setAd(this.ad)
    }
  }
</script>

<style lang="scss" scoped>
</style>
