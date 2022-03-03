<template>
  <div id="create-admin">
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
      <label for="phone">Celular</label>
      <input type="tel" class="form-control" :class="{'is-invalid':errors.phone}" id="phone" v-model="phone" maxlength="10" required>
      <div v-if="errors.phone" class="invalid-feedback">{{errors.phone[0]}}</div>
    </div>

    <div class="form-group">
      <label for="password">Contraseña</label>
      <input type="password" class="form-control" :class="{'is-invalid':errors.password}" id="password" v-model="password" required>
      <div v-if="errors.password" class="invalid-feedback">{{errors.password[0]}}</div>
    </div>
    
    <div class="form-group">
      <label for="rate">Tasa</label>
      <input type="integer" class="form-control" :class="{'is-invalid':errors.rate}" v-model="rate" required>
      <div v-if="errors.rate" class="invalid-feedback">{{errors.rate[0]}}</div>
    </div>

    <div class="form-group text-right">
      <button v-if="!editing" class="btn btn-primary" @click="storeFreelancer">Enviar</button>
      <button v-else class="btn btn-primary" @click="updateFreelancer">Actualizar</button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    'freelancerToEdit': Object,
    'editing': Boolean,
  },
  data(){
    return {
      'errors': [],
      'name': '',
      'email': '',
      'phone': '',
      'password': '',
      'rate': ''
    }
  },
  watch:{
    freelancerToEdit(newFreelancer, oldFreelancer){
      if( newFreelancer ){
        this.name  = newFreelancer.name
        this.email = newFreelancer.email
        this.rate  = newFreelancer.rate
        this.phone = newFreelancer.phone
      }else {
        this.clearForm()
      }
    }
  },
  methods:{
    storeFreelancer(){
      let data = this.loadData();
      axios.post('/admin/freelancers', data).then((response)=>{
        this.$emit('freelancerStored', response.data.data)
        this.clearForm()

        $('#exampleModal').modal('hide')
      })
      .catch((error)=>{
        this.errors = error.response.data.errors
      })
    },
    updateFreelancer(){
      let data = this.loadData();
      data.append('_method', 'PUT');

      axios.post('/admin/freelancers/'+this.freelancerToEdit.id, data).then((response)=>{
        console.log( response.data.data );
        this.$emit('freelancerUpdated', response.data.data)
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
      data.append('rate', this.rate);
      data.append('phone', (this.phone) ? this.phone : '');
      return data;
    },
    clearForm(){
      this.email = ''
      this.password = ''
      this.rate = ''
      this.phone = null
      this.errors = []
    }
  }
} 
</script>
