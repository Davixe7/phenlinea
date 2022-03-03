<template>
  <div id="create-extension">
    <form action="#" ref="form">
      <div class="form-group">
        <label for="name">Nro. Apartamento</label>
        <input type="number" class="form-control" :class="{'is-invalid':errors.name}" id="name" v-model="name" maxlength="5" required>
        <div v-if="errors.name" class="invalid-feedback">{{ errors.name[0] }}</div>
      </div>
      <div class="form-group">
        <label for="phone_1">Teléfono 1</label>
        <div class="input-group">
          <span class="input-group-prepend">
            <span class="input-group-text">+57</span>
          </span>
          <input type="search" class="form-control" :class="{'is-invalid':errors.phone_1}" id="phone_1" v-model="phone_1" placeholder="0000-000-0000" maxlength="10" minlength="10" required>
          <div v-if="errors.phone_1" class="invalid-feedback">{{ errors.phone_1[0] }}</div>
        </div>
      </div>
      <div class="form-group">
        <label for="phone_1">Teléfono 2</label>
        <div class="input-group">
          <span class="input-group-prepend">
            <span class="input-group-text">+57</span>
          </span>
          <input type="search" class="form-control" :class="{'is-invalid':errors.phone_2}" id="phone_2" v-model="phone_2" maxlength="10" minlength="10" placeholder="0000-000-0000">
          <div v-if="errors.phone_2" class="invalid-feedback">{{ errors.phone_2[0] }}</div>
        </div>
      </div>
      <div class="form-group text-right">
        <button type="button" v-if="!editing" class="btn btn-primary" @click="storeExtension">Enviar</button>
        <button type="button" v-else class="btn btn-primary" @click="updateExtension">Actualizar</button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  props: {
    'extToEdit': Object,
    'editing': Boolean,
  },
  data(){
    return {
      'extensionId': null,
      'name': '',
      'phone_1': '',
      'phone_2': '',
      errors: []
    }
  },
  watch:{
    extToEdit(newExt, oldExt){
      if( newExt ){
        this.name = newExt.name
        this.phone_1 = newExt.phone_1
        this.phone_2 = newExt.phone_2
      }else {
        this.clearForm()
      }
    }
  },
  methods:{
    storeExtension(){
      if( !this.validateForm() ){
        return false;
      }
      let data = this.loadData();
      axios.post('/extensions', data).then((response)=>{
        this.$emit('extensionStored', response.data.data)
        this.clearForm()
        $('#exampleModal').modal('hide')
      })
      .catch((error)=>{
        if( error.response.status == 403 ){
          this.$toasted.error('No tienes permiso para realizar esta acción', {position:'bottom-left'})
        }else{
          this.errors = error.response.data.errors
        }
      });
    },
    updateExtension(){
      if( !this.validateForm() ){
        return false;
      }
      let data = this.loadData();
      data.append('_method', 'PUT');

      axios.post('/extensions/'+this.extToEdit.id, data).then((response)=>{
        this.$emit('extensionUpdated', response.data.data)
        this.clearForm()
        $('#exampleModal').modal('hide')
      })
      .catch((error)=>{
        if( error.response.status == 403 ){
          this.$toasted.error('No tienes permiso para realizar esta acción', {position:'bottom-left'})
        }else{
          this.errors = error.response.data.errors
        }
      });
    },
    loadData(){
      this.errors = []
      let data = new FormData();
      data.append('name', this.name);
      data.append('phone_1', this.phone_1);
      data.append('phone_2', (this.phone_2) ? this.phone_2 : '');
      return data;
    },
    validateForm(){
      this.$refs.form.reportValidity();
      return this.$refs.form.checkValidity();
    },
    clearForm(){
      this.name = ''
      this.phone_1 = ''
      this.phone_2 = ''
      this.errors = []
    }
  }
}
</script>
<style>
  .input-group-append {
    background: #fff;
    font-size: 1em;
    height: 1.6em;
    padding: 4px 10px 5px 10px;
  }
  .update-ext-wrap {
    color: #DFDFDF;
    cursor: pointer;
    display: inline-block;
  }
  .update-ext-wrap:hover {
    color: #9f9f9f;
  }
</style>
