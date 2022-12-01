<template>
  <div id="create-admin">
    <div class="form-group">
      <label for="name">Nombre</label>
      <input type="text" class="form-control" id="name" v-model="name">
    </div>
    <div class="row">
      <div class="form-group col">
        <label for="nit">NIT</label>
        <input type="text" class="form-control" :class="{'is-invalid':errors.nit}" id="nit" v-model="nit">
        <div v-if="errors.nit" class="invalid-feedback">{{errors.nit[0]}}</div>
      </div>
      <div class="form-group col">
        <label for="email">Email</label>
        <input type="email" class="form-control" :class="{'is-invalid':errors.contact_email}" id="contact_email" v-model="contact_email">
        <div v-if="errors.contact_email" class="invalid-feedback">{{errors.contact_email[0]}}</div>
      </div>
    </div>
    <div class="form-group">
      <label for="address">Dirección</label>
      <input type="text" class="form-control" id="address" v-model="address">
    </div>

    <div class="row">
      <div class="form-group col">
        <label for="phone">Celular</label>
        <input type="tel" class="form-control" :class="{'is-invalid':errors.phone}" id="phone" v-model="phone">
        <div v-if="errors.phone" class="invalid-feedback">{{errors.phone[0]}}</div>
      </div>

      <div class="form-group col">
        <label for="phone_2">Celular 2</label>
        <input type="tel" class="form-control" :class="{'is-invalid':errors.phone_2}" id="phone_2" v-model="phone_2">
        <div v-if="errors.phone_2" class="invalid-feedback">{{errors.phone_2[0]}}</div>
      </div>
    </div>

    <div class="row">
      <div class="form-group col">
        <label for="email">Usuario</label>
        <input type="email" class="form-control" :class="{'is-invalid':errors.email}" id="email" v-model="email">
        <div v-if="errors.email" class="invalid-feedback">{{errors.email[0]}}</div>
      </div>
      
      <div class="form-group col">
        <label for="password">Contraseña</label>
        <input type="password" class="form-control" :class="{'is-invalid':errors.password}" id="password" v-model="password">
        <div v-if="errors.password" class="invalid-feedback">{{errors.password[0]}}</div>
      </div>
    </div>
    
    <div v-if="editing" class="form-group">
      <label for="status">Status</label>
      <select v-model="status" id="status" class="form-control">
        <option :value.number="1">Solvente</option>
        <option :value.number="0">Pendiente</option>
      </select>
    </div>
    
    <div class="form-group">
      <label for="picture">
        Foto de Perfil
        <span v-if="adminToEdit && adminToEdit.picture">
          <a :href="'/'+adminToEdit.picture" target="_blank">
            Ver actual
          </a>
        </span>
      </label>
      <input type="file" class="form-control" name="picture" ref="pictureInput" @change="loadPicture">
    </div>
    
    <div v-if="editing" class="form-check form-check-inline">
      <input class="form-check-input" type="checkbox" id="inlineCheckbox1" v-model="sms_enabled">
      <label class="form-check-label" for="inlineCheckbox1">Habilitar SMS masivo</label>
    </div>

    <div class="form-group text-right">
      <button v-if="!editing" class="btn btn-primary" @click="storeAdmin">Enviar</button>
      <button v-else class="btn btn-primary" @click="updateAdmin">Actualizar</button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    'adminToEdit': Object,
    'editing': Boolean,
  },
  data(){
    return {
      'errors': [],
      'adminId': null,
      'name': '',
      'nit': '',
      'address': '',
      'contact_email': '',
      'phone': '',
      'phone_2': '',
      'email': '',
      'password': '',
      'status': Number,
      'sms_enabled': false,
      'new_picture': null
    }
  },
  watch:{
    adminToEdit(newAdmin, oldAdmin){
      if( newAdmin ){
        this.name = newAdmin.name
        this.nit = newAdmin.nit
        this.address = newAdmin.address
        this.phone = newAdmin.phone
        this.phone_2 = newAdmin.phone_2
        this.email = newAdmin.email
        this.password = newAdmin.password
        this.contact_email = newAdmin.contact_email
        this.status = newAdmin.status
        this.sms_enabled = newAdmin.sms_enabled
      }else {
        this.clearForm()
      }
    }
  },
  methods:{
    loadPicture(){
      this.new_picture = this.$refs.pictureInput.files[0];
    },
    storeAdmin(){
      let data = this.loadData();
      axios.post('/admin/admins', data).then((response)=>{
        this.$emit('adminStored', response.data.data)
        this.clearForm()

        $('#exampleModal').modal('hide')
      })
      .catch((error)=>{
        this.errors = error.response.data.errors
      })
    },
    updateAdmin(){
      let data = this.loadData();
      data.append('_method', 'PUT');

      axios.post('/admin/admins/'+this.adminToEdit.id, data).then((response)=>{
        this.$emit('adminUpdated', response.data.data)
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
      data.append('name', this.name)
      data.append('nit', this.nit)
      data.append('address', this.address)
      data.append('phone', this.phone)
      data.append('phone_2', (this.phone_2) ? this.phone_2 : '')
      data.append('email', this.email)
      data.append('password', (this.password) ? this.password : '')
      data.append('contact_email', this.contact_email)
      data.append('status', Number(this.status) )
      data.append('sms_enabled', (this.sms_enabled) ? 1 : 0)
      data.append('picture', this.new_picture)
      return data;
    },
    clearForm(){
      this.name = ''
      this.nit = ''
      this.address = ''
      this.phone = ''
      this.phone_2 = ''
      this.email = ''
      this.password = ''
      this.contact_email = ''
      this.errors = []
      this.$refs.pictureInput.value = ''
    }
  }
}
</script>
