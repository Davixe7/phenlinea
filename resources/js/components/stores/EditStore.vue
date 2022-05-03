<template>
  <div class="create-store">
    <div class="card">
      <div class="card-header">
        <div v-if="false" class="col-md-6 text-right d-flex align-items-center justify-content-end">
          <b>{{ password }}</b>
          <div class="options-dropdown">
            <div class="dropdown">
              <span class="dropdown-toggler" data-toggle="dropdown">
                <i class="material-icons">more_vert</i>
              </span>
              <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="#" @click.prevent="resetPassword()">Restablecer contraseña</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form @submit.prevent ref="StoreForm">
          
          <div class="row">
            <label class="col-md-7" for="category">Categoría</label>
            <div class="col-md-5 form-group">
              <select v-model="category" class="form-control" :class=" {'is-invalid' : errors.category }">
                <option v-for="cat in categories" :value="cat">{{ cat }}</option>
                <span v-if="errors.category" class="text-danger">{{ errors.category[0] }}</span>
              </select>
            </div>
          </div>
          
        <div class="row">
          <div class="form-group col-md-7">
            <label for="name">Nombre</label>
            <input type="text" class="form-control" v-model="name" :class=" {'is-invalid' : errors.name }" required>
            <span v-if="errors.category" class="text-danger">{{ errors.category[0] }}</span>
          </div>
          <div class="form-group col-md-5">
            <label for="nit">NIT</label>
            <input type="tel" class="form-control" v-model="nit" :class=" {'is-invalid' : errors.nit }" required>
            <span v-if="errors.nit" class="text-danger">{{ errors.nit[0] }}</span>
          </div>
        </div>
        
        <div class="form-group">
          <label for="description">Descripción</label>
          <textarea rows="2" class="form-control" v-model="description" :class=" {'is-invalid' : errors.description }" required></textarea>
          <span v-if="errors.description" class="text-danger">{{ errors.description[0] }}</span>
        </div>
        
        <div class="row">
          <div class="form-group col-md-6">
            <label for="phone_1">Teléfono</label>
            <input type="tel" class="form-control" v-model="phone_1" :class=" {'is-invalid' : errors.phone_1 }" minlength="12" maxlength="12" required>
            <span v-if="errors.phone_1" class="text-danger">{{ errors.phone_1[0] }}</span>
          </div>
          <div class="form-group col-md-6">
            <label for="email">Email</label>
            <input type="email" class="form-control" v-model="email" :class=" {'is-invalid' : errors.email }" required>
            <span v-if="errors.email" class="text-danger">{{ errors.email[0] }}</span>
          </div>
        </div>
          
        <span @click="activePanel='schedule'" class="pill" :class="{active:activePanel=='schedule'}">
          <i class="material-icons">alarm</i> 
          Horario
        </span>
        <span @click="activePanel='gallery'" class="pill" :class="{active:activePanel=='gallery'}">
          <i class="material-icons">image</i> 
          Galería
        </span>
        <span @click="activePanel='logo'" class="pill" :class="{active:activePanel=='logo'}">
          <i class="material-icons">tag_faces</i> 
          Logo
        </span>
        <span @click="activePanel='location'" class="pill" :class="{active:activePanel=='location'}">
          <i class="material-icons">location_on</i> 
          Ubicación
        </span>
        
        <div class="form-group panel" :class="{active:activePanel=='schedule'}">
          <label for="schedule" class="d-block">Días laborables</label>
          <week-scheduler :schedule="workdays" v-model="workdays"/>
        </div>
        
        <div class="form-group panel" :class="{active:activePanel=='gallery'}">
          <label class="d-block">Adjuntas actualmente</label>
          <div class="well">
            <pics-list :pictures="storePictures" @removePic="deletePicture"/>
          </div>
          
          <label class="d-block">Adjuntar imágenes</label>
          <div class="well">
            <file-input @filesLoaded="f=>pictures=f"/>
            <pics-list :pictures="pictures" @removePic="p=>pictures=pictures.filter(f=>f.name != p.name)"/>
          </div>
        </div>
        
        <div class="form-group panel" :class="{active:activePanel=='logo'}">
          <store-logo :id="commerce.id" :logo="logo" v-model="logo"></store-logo>
        </div>
        
        <div class="form-group panel" :class="{active:activePanel=='location'}">
          <div class="form-group">
            <label for="address">Dirección</label>
            <input type="text" class="form-control" v-model="address" :class=" {'is-invalid' : errors.address }" required>
            <span v-if="errors.address" class="text-danger">{{ errors.address[0] }}</span>
          </div>
          <div class="row">
            <div class="form-group col">
              <label for="lat">Lat</label>
              <input type="text" class="form-control" v-model="lat" :class=" {'is-invalid' : errors.lat }" minlength="2" maxlength="10">
              <span v-if="errors.lat" class="text-danger">{{ errors.lat[0] }}</span>
            </div>
            <div class="form-group col">
              <label for="lng">Lng</label>
              <input type="text" class="form-control" v-model="lng" :class=" {'is-invalid' : errors.lng }" minlength="2" maxlength="10">
              <span v-if="errors.lng" class="text-danger">{{ errors.lng[0] }}</span>
            </div>
          </div>
        </div>
        
        <div class="d-flex align-items-center justify-content-end">
          <v-btn color="primary" dark @click="update" :loading="saving">
            Actualizar
          </v-btn>
        </div>
      </form>
    </div>
  </div>
</div>
</template>

<script>
export default {
  props: {
    url: {type: String, default:'/admin/stores/'},
    commerce: {
      type: Object,
      default: ()=>{return {}}
    }
  },
  name: 'EditStore',
  data(){return{
    activePanel: '',
    categories: [ 'Comidas', 'Ferreterías', 'Carnicerías', 'Supermercados', 'Licores', 'Legumbrerias', 'Tiendas', 'Otros' ],
    
    nit: '',
    name: '',
    description: '',
    phone_1: '',
    email: '',
    address: '',
    logo: '',
    category: '',
    lat: null,
    lng: null,
    workdays: [],
    pictures: [],
    password: null,
    
    storePictures: [],
    errors: {},
    saving: false
  }},
  methods:{
    deletePicture(picture){
      let data = { _method: 'PUT', picture:picture.path }
      axios.post(`${this.url}${this.commerce.id}/pictures`, data).then(response=>{
        this.storePictures = Object.values(response.data.data)
      })
    },
    update(){
      if( !this.$refs.StoreForm.reportValidity() ){return}
      let data = this.loadData()
      data.append('_method', 'PUT')
      axios.post(`${this.url}${this.commerce.id}`, data).then(response=>{
        this.$toasted.show('Comercio actualizado exitosamente',{position:'bottom-center'})
        this.$emit('storeUpdated', response.data.data)
        this.clearForm()
        this.setCommerce(response.data.data)
      },error=>{
        this.$toasted.error('Error al actualizar', {position:'bottom-center'})
        if(error.response.data.errors){
          this.errors = error.response.data.errors
        }
      })
      .then(()=>{
        this.saving = false
      })
    },
    loadData(){
      this.saving = true
      let atts = ['nit', 'name', 'description', 'phone_1', 'email', 'address', 'category', 'lat', 'lng'];
      let data = new FormData()
      atts.forEach(att=>{ data.append(att, this[att]) })
      this.pictures.forEach(picture => data.append('pictures[]', picture))
      data.append('schedule', JSON.stringify(this.workdays))  
      return data
    },
    clearForm(){
      let atts = ['nit', 'name', 'description', 'phone_1', 'email', 'address', 'category', 'lat', 'lng', 'schedule', 'logo'];
      atts.forEach(att => this[att] = '')
      this.storeLogo = {}
      this.pictures = []
    },
    setCommerce(src){
      let commerce = (src && src.id) ? src : this.commerce
      let atts = ['nit', 'name', 'description', 'phone_1', 'email', 'address', 'category', 'lat', 'lng', 'logo'];
      atts.forEach(att=>this[att] = commerce[att])
      this.workdays      = commerce.schedule
      this.storePictures = Object.values(commerce.pictures)
      this.storeLogo     = commerce.logo
      this.password      = commerce._password
    },
    resetPassword(){
        if( confirm('¿Seguro que desea restablecer la contraseña?') ){
          axios.post(`/admin/stores/${this.commerce.id}/resetpassword`, {'_method':'PUT'})
          .then(response=>{
            this.password = response.data.data
          },error=>{
            console.log(error);
          })
        }
      },
  },
  mounted(){
    this.setCommerce()
  }
}
</script>

<style lang="css" scoped>
  .pill.active {
    background: #646464;
    color: #fff;
  }
  .panel {
    display: none;
  }
  .panel.active {
    display: block;
  }
  .map {
    background: darkgray;
    height: 300px
  }
  
  label {
    font-size: .9em;
    color: #212121;
    font-weight: 800;
  }
  .form-group {
    margin-bottom: 15px;
  }
</style>
