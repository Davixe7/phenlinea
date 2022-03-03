<template>
  <div id="create-admin">
    <div class="form-group">
      <label for="admin_id">Administrador</label>
      <select name="admin_id" id="admin_id" class="form-control" v-model="adminId" required>
        <option v-for="admin in admins" :value="admin.id">{{ admin.id + ' ' + admin.name }}</option>
      </select>
    </div>
    
    <div class="form-group">
      <label for="name">Nombre</label>
      <input type="text" class="form-control" :class="{'is-invalid':errors.name}" id="name" v-model="name" required>
      <div v-if="errors.name" class="invalid-feedback">{{errors.name[0]}}</div>
    </div>
    
    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" class="form-control" :class="{'is-invalid':errors.email}" id="email" v-model="email" required>
      <div v-if="errors.email" class="invalid-feedback">{{errors.email[0]}}</div>
    </div>

    <div class="form-group">
      <label for="password">Contraseña</label>
      <input type="password" class="form-control" :class="{'is-invalid':errors.password}" id="password" v-model="password" required>
      <div v-if="errors.password" class="invalid-feedback">{{errors.password[0]}}</div>
    </div>

    <div class="form-group text-right">
      <button v-if="!editing" class="btn btn-primary" @click="storePorteria">Enviar</button>
      <button v-else class="btn btn-primary" @click="updatePorteria">Actualizar</button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    'porteriaToEdit': Object,
    'editing': Boolean,
  },
  data(){
    return {
      'admins': [],
      'errors': [],
      'adminId': null,
      'porteriaId': null,
      'name': '',
      'email': '',
      'password': '',
    }
  },
  watch:{
    porteriaToEdit(newPorteria, oldPorteria){
      if( newPorteria ){
        this.name = newPorteria.name
        this.email = newPorteria.email
        this.password = newPorteria.password
        this.adminId  = newPorteria.admin_id
      }else {
        this.clearForm()
      }
    }
  },
  methods:{
    storePorteria(){
      let data = this.loadData();
      axios.post('/admin/porterias', data).then((response)=>{
        this.$emit('porteriaStored', response.data.data)
        this.clearForm()

        $('#exampleModal').modal('hide')
      })
      .catch((error)=>{
        this.errors = error.response.data.errors
      })
    },
    updatePorteria(){
      let data = this.loadData();
      data.append('_method', 'PUT');

      axios.post('/admin/porterias/'+this.porteriaToEdit.id, data).then((response)=>{
        this.$emit('porteriaUpdated', response.data.data)
        this.clearForm()

        $('#exampleModal').modal('hide')
      })
      .catch((error)=>{
        if( error.status == 403 ){
          this.$toasted.error('No tienes permisos para realizar esta acción', {'position':'bottom-left'})
        }else{
          this.errors = error.response.data.errors
        }
      })
    },
    loadData(){
      let data = new FormData();
      data.append('name', this.name);
      data.append('email', this.email);
      data.append('password', (this.password) ? this.password : '');
      data.append('admin_id', this.adminId);
      return data;
    },
    clearForm(){
      this.email = ''
      this.password = ''
      this.errors = []
    }
  },
  mounted(){
    axios.get('/admin/admins/list').then((response)=>{
      this.admins = response.data.data
      this.adminId = this.admins[0].id
    })
    .catch((error)=>{
      this.errors = error.response.data.errors
    })
  }
} 
</script>
