<template>
  <div class="create-store">
    <div class="card">
      <div class="card-body">
        <form @submit.prevent ref="StoreForm">
          <div class="form-group">
            <label for="name">Nombre</label>
            <input type="text" class="form-control" v-model="name" :class=" {'is-invalid' : errors.name }" required>
            <span v-if="errors.category" class="text-danger">{{ errors.category[0] }}</span>
          </div>
          <div class="row">
            <div class="form-group col-md-7">
              <label for="category">Categoría</label>
              <select v-model="category" class="form-control" :class=" {'is-invalid' : errors.category }">
                <option v-for="cat in categories" :value="cat">{{ cat }}</option>
                <span v-if="errors.category" class="text-danger">{{ errors.category[0] }}</span>
              </select>
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
              <input type="tel" class="form-control" v-model="phone_1" :class=" {'is-invalid' : errors.phone_1 }" minlength="10" maxlength="10" required>
              <span v-if="errors.phone_1" class="text-danger">{{ errors.phone_1[0] }}</span>
            </div>
            <div class="form-group col-md-6">
              <label for="email">Email</label>
              <input type="email" class="form-control" v-model="email" :class=" {'is-invalid' : errors.email }" required>
              <span v-if="errors.email" class="text-danger">{{ errors.email[0] }}</span>
            </div>
          </div>
          
          <div class="row">
            <div class="form-group col-8">
              <label for="address">Dirección</label>
              <input type="text" class="form-control" v-model="address" :class=" {'is-invalid' : errors.address }" required>
              <span v-if="errors.address" class="text-danger">{{ errors.address[0] }}</span>
            </div>
            <div class="col-4">
              <div class="row">
                <div class="form-group col">
                  <label for="lat">Lat</label>
                  <input type="text" class="form-control" v-model.number="lat" :class=" {'is-invalid' : errors.lat }" minlength="2" maxlength="10">
                  <span v-if="errors.lat" class="text-danger">{{ errors.lat[0] }}</span>
                </div>
                <div class="form-group col">
                  <label for="lng">Lng</label>
                  <input type="text" class="form-control" v-model.number="lng" :class=" {'is-invalid' : errors.lng }" minlength="2" maxlength="10">
                  <span v-if="errors.lng" class="text-danger">{{ errors.lng[0] }}</span>
                </div>
              </div>
            </div>
          </div>
          
          <span class="pill" @click="activePanel='schedule'">
            <i class="material-icons">alarm</i> 
            Horario
          </span>
          <span class="pill" @click="activePanel='gallery'">
            <i class="material-icons">image</i> 
            Galería
          </span>
          <span class="pill" @click="activePanel='logo'">
            <i class="material-icons">tag_faces</i> 
            Logo
          </span>
          
        <div class="form-group panel" :class="{active:activePanel=='schedule'}">
          <label for="schedule" class="d-block">Días laborables</label>
          <week-scheduler v-model="workdays"/>
        </div>
        <div class="form-group panel" :class="{active:activePanel=='gallery'}">
          <div class="well">
            <file-input @filesLoaded="(f)=>{pictures=f}"/>
            <pics-list :pictures="pictures" @removePic="removePic"/>
          </div>
        </div>
        <div class="form-group panel" :class="{active:activePanel=='logo'}">
          <store-logo :logo="null" v-model="logo"></store-logo>
        </div>
        
        <div class="d-flex align-items-center justify-content-end">
          <v-btn text href="/admin/stores">Volver</v-btn>
          <v-btn dark @click="store" :loading="saving">Registrar</v-btn>
        </div>
      </form>
    </div>
  </div>
</div>
</template>

<script>
export default {
  name: 'CreateStore',
  data(){return{
    activePanel: 'schedule',
    categories: ['Comidas', 'Ferreterías', 'Carnicerías', 'Supermercados', 'Licores', 'Legumbrerias', 'Tiendas', 'Otros'],
    errors: {},
    saving: false,
    
    nit: '',
    name: '',
    description: '',
    phone_1: '',
    email: '',
    address: '',
    logo: '',
    pictures: [],
    category: 'Comidas',
    lat: null,
    lng: null,
    workdays: [],
  }},
  methods:{
    removePic(name){
      this.pictures = this.pictures.filter(f => f.name != name)
    },
    store(){
      if( !this.$refs.StoreForm.reportValidity() ){ return false }
      axios.post('/admin/stores',this.loadData()).then(response=>{
        this.$toasted.show({position:'bottom-center'},'Comercio registrado exitosamente')
        window.location.href = '/admin/stores/' + response.data.data.id + '/edit'
      },error=>{
        this.errors = error.response.data.errors
      })
      .then(()=>{
        this.saving = false
      })
    },
    loadData(){
      this.saving = true
      let atts = ['nit', 'name', 'description', 'phone_1', 'email', 'address', 'category', 'lat', 'lng', 'logo'];
      let data = new FormData()
      atts.forEach(att=>{ data.append(att, this[att]) })
      this.pictures.forEach(picture => data.append('pictures[]', picture))
      data.append('logo', this.logo) 
      data.append('schedule', JSON.stringify(this.workdays))  
      return data
    },
    clearForm(){
      let atts = ['nit', 'name', 'description', 'phone_1', 'email', 'address', 'category', 'lat', 'lng', 'schedule', 'logo'];
      atts.forEach(att => this[att] = '')
      this.pictures = []
    },
  }
}
</script>

<style lang="css" scoped>
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
</style>
