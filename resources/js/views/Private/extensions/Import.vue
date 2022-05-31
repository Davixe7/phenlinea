<template>
  <div id="extensions">
    <h1>Importar extensiones en lote</h1>
    <h4>Seleccionar Unidad</h4>
    <div class="form-group" v-if="admins">
      <v-select :options="admins" v-model="admin" required></v-select>
    </div>
    <input ref="fileInput" type="file" name="batch" @change="updateFile" required>
    <button type="button" class="btn btn-primary" @click="importExtensions" :disabled="!file || sending">Enviar</button>
    
    <hr>
    <ul v-if="errors" class="mb-0 pl-3">
      <li v-for="error in errors">{{ error }}</li>
    </ul>
      
  </div>
</template>

<script>
import vSelect from 'vue-select'
export default {
  name: 'ExtensionsImport',
  components: {vSelect},
  data(){
    return {
      user: null,
      admins: [],
      admin: null,
      errors: [],
      file: null,
      sending: false
    }
  },
  methods:{
    updateFile(){
      this.file = this.$refs.fileInput.files[0]
    },
    importExtensions(){
      this.errors = []
      this.sending = true
      
      let data = new FormData;
      data.append('admin_id', this.admin.code)
      data.append('batch', this.file)
      
      axios.post('/extensions/import', data)
      .then((response)=>{
        this.errors = response.data.errors
        this.$toasted.success('Importación completada exitosamente', {position:'bottom-left'})
        
        this.$refs.fileInput.value = ""
        this.file = null
      })
      .catch((error)=>{
        this.$toasted.error('Importación fallida', {position:'bottom-left'})
        let errors = error.response.data.errors
        this.errors = Object.keys( errors ).map((_)=>{
          return errors[_][0]
        })
      })
      .finally(()=>{
        this.sending = false
      })
    }
  },
  mounted(){
    axios.get('/admins/list').then((response)=>{
      let admins = response.data.data
      this.admins = admins.map((a)=>{
        return { "label" : a.name, "code"  : a.id }
      })
    })
  }
}
</script>

<style>
  .filled {
    color: #06a906;
  }
  .empty {
    color: #c0392b;
  }
</style>
