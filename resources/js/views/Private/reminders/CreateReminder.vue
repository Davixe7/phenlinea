<template>
  <div id="create-reminder">
    <div class="form-row form-group">
      <div class="col">Apartamento</div>
      <div class="col text-right">
        <span v-if="extension" class="extension-id">{{ extension.name }}</span>
        <span v-else class="undefined-extension">
          <i class="material-icons">info</i> 
          Sin definir
        </span>
      </div>
    </div>
    <form ref="createReminderForm" @submit.prevent="storeReminder">
      <div class="form-group">
        <label for="title">Título</label>
        <input type="text" class="form-control" v-model="title" placeholder="Título de la notificación" :disabled="disabled" required>
      </div>
      <div class="form-group">
        <label for="description">Descripción</label>
        <textarea rows="3" class="form-control" v-model="description" placeholder="Escriba aquí..." :disabled="disabled" required>{{ description }}</textarea>
      </div>
      
      <div v-if="reminderPictures && reminderPictures.length">
        <label>Imagenes actuales</label>
        <PicturesList :pictures="reminderPictures" @deletePicture="deletePicture"/>
      </div>
      
      <div class="form-group">
        <label for="">Adjuntar imagen</label>
        <MultipleFilesInput :clear="clear" @filesUploaded="updateFiles"/>
      </div>
      
      <div v-if="!disabled" class="text-right">
        <button type="button" class="btn btn-link" @click="clearForm">Cancelar</button>
        <button type="submit" v-show="!editing" class="btn btn-primary">Guardar</button>
        <button type="button" v-show="editing"  @click="updateReminder" class="btn btn-primary">Actualizar</button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  props: {
    extension: {
      type: Object,
      default:()=>{}
    },
    reminder: {
      type: Object,
      default:()=>{}
    }
  },
  computed:{
    disabled(){
      return (this.extension && this.extension.id) ? false : true
    }
  },
  name: 'CreateReminder',
  data(){
    return {
      title: '',
      description: '',
      pictures: [],
      editing: false,
      
      reminderPictures: [],
      clear: 0
    }
  },
  watch:{
    reminder(newVal){
      if( newVal && newVal.id ){
        this.title = newVal.title
        this.description = newVal.description
        this.reminderPictures = newVal.pictures
        this.editing = true
        return
      }
      this.clearForm()
    }
  },
  methods:{
    deletePicture(p){
      let data = { picture:p.path, '_method':'PUT' }
      axios.post(`/reminders/${this.reminder.id}/deletepicture`, data)
      .then(response=>{
        this.$toasted.show('Imagen removida con éxito')
        this.$emit('updateReminder', response.data.data, true)
      },error=>{
        console.log( error );
      })
    },
    unsetReminder(){
      this.$emit('reminderUnset')
    },
    updateFiles(files){
      this.pictures = files
    },
    storeReminder(){
      if( this.$refs.createReminderForm.checkValidity() ){
        let data = this.loadData()
        axios.post('reminders', data).then(response=>{
          this.$emit('storeReminder', response.data.data)
          this.$toasted.success('Notificación registrada exitosamente')
        },error=>{
          console.log(error);
        }).then(()=>{
          this.clearForm()
        })
      }
    },
    updateReminder(){
      let data = this.loadData()
      data.append('_method', 'PUT')
      axios.post(`reminders/${this.reminder.id}`, data).then(response=>{
        this.$emit('updateReminder', response.data.data)
        this.$toasted.success('Notificación actualizada exitosamente')
      },error=>{
        console.log(error);
      }).then(()=>{
        this.clearForm()
      })
    },
    loadData(){
      let data = new FormData()
      data.append('title', this.title)
      data.append('description', this.description)
      data.append('extension_id', this.extension.id)
      this.pictures.forEach((pic, i) => {
        data.append('pictures[]', pic.file)
      });
      return data
    },
    clearForm(){
      this.title = ''
      this.description = ''
      this.pictures = []
      this.editing = false
      this.reminderPictures = []
      
      this.clear = Math.random(0,100)
      
      this.unsetReminder()
    }
  }
}
</script>

<style lang="sass" scoped>
  .extension-id
    font-size: 1.2em
    font-weight: 500
  .undefined-extension
    font-weight: 500
    color: #4d889a
    font-size: 1em
</style>
