<template>
  <div id="create-user">
    <div class="form-group">
      <label for="name">Nombre</label>
      <input type="text" class="form-control" id="name" v-model="name">
    </div>
    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" class="form-control" :class="{'is-invalid':errors.email}" id="email" v-model="email">
      <div v-if="errors.email" class="invalid-feedback">{{ errors.email[0] }}</div>
    </div>

    <div class="form-group">
      <label for="password">Contraseña</label>
      <input type="password" class="form-control" :class="{'is-invalid':errors.password}" id="password" v-model="password">
      <div v-if="errors.password" class="invalid-feedback">{{ errors.password[0] }}</div>
    </div>

    <div class="form-group text-right">
      <button v-if="!editing" class="btn btn-primary" @click="storeUser">Enviar</button>
      <button v-else class="btn btn-primary" @click="updateUser">Actualizar</button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    'userToEdit': Object,
    'editing': Boolean,
  },
  data(){
    return {
      'userId': null,
      'name': '',
      'email': '',
      'password': '',
      'errors': []
    }
  },
  watch:{
    userToEdit(newUser, oldUser){
      if( newUser ){
        this.name = newUser.name
        this.email = newUser.email
        this.password = newUser.password
      }else {
        this.clearForm()
      }
    }
  },
  methods:{
    storeUser(){
      let data = this.loadData();
      axios.post('/admin/users', data).then((response)=>{
        this.$emit('userStored', response.data.data)
        this.clearForm()

        $('#exampleModal').modal('hide')
      })
      .catch((err)=>{
        this.errors = err.response.data.errors
      });
    },
    updateUser(){
      let data = this.loadData();
      data.append('_method', 'PUT');

      axios.post('/admin/users/'+this.userToEdit.id, data).then((response)=>{
        this.$emit('userUpdated', response.data.data)
        this.clearForm()

        $('#exampleModal').modal('hide')
      })
      .catch((error)=>{
        console.log( error.response.status );
        if( error.response.status == 403 ){
          this.$toasted.error('No tienes permisos para realizar esta acción', {'position':'bottom-left'})
        }else{
          this.errors = error.response.data.errors
        }
      });
    },
    loadData(){
      let data = new FormData();
      data.append('name', this.name);
      data.append('email', this.email);
      data.append('password', this.password);
      return data;
    },
    clearForm(){
      this.name = ''
      this.email = ''
      this.password = ''
      this.errors = []
    }
  }
}
</script>
