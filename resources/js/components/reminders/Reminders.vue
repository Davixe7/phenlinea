<template>
  <div id="reminders">
    <h1>Notificaciones</h1>
    <hr>
    <div class="row">
      <div class="col-md-5">
        <div class="card">
          <div class="card-body">
            <span class="form-section-title">Crear notificación</span>
            <CreateReminder
              :extension="extension"
              :reminder="reminder"
              @reminderUnset="unsetReminder"
              @storeReminder="pushReminder"
              @updateReminder="refreshReminder"/>
          </div>
        </div>
      </div>
      <div class="col-md-6 offset-md-1">
        <div class="form-row">
          <span class="col form-section-title">Todas las notificaciones</span>
          <div class="col text-right">
            <i class="material-icons">meeting_room</i> 
            <v-select style="width:200px;" class="mb-2 d-inline-block" :options="extensions" :label="'name'" :placeholder="'Seleccione apartamento'" v-model="extension" required></v-select>
          </div>
        </div>
        <RemindersList :loading="loadingReminders" :reminders="reminders" :isAdmin="true" @deleteReminder="deleteReminder" @editReminder="editReminder"/>
      </div>
    </div>
  </div>
</template>

<script>
import vSelect from 'vue-select'
import CreateReminder from './CreateReminder.vue'
import RemindersList  from './RemindersList.vue'
export default {
  components: { CreateReminder, vSelect, RemindersList },
  name: 'Reminders',
  data(){
    return {
      reminders: [],
      reminder: {},
      
      extensions: [],
      extension: null,
      
      loader: {},
      loadingReminders: false
    }
  },
  watch:{
    extension(newVal){
      if(newVal && (newVal.id || newVal.id == 0)){
        this.fetchReminders( newVal.id )
        return
      }
      this.reminders = []
    }
  },
  methods:{
    unsetReminder(){
      this.reminder = null
    },
    pushReminder(reminder){
      this.reminders.unshift(reminder)
    },
    editReminder(reminder){
      this.reminder = reminder
    },
    refreshReminder(reminder, keep){
      this.reminders = this.reminders.map( r => reminder.id == r.id ? reminder : r)
      if(keep){
        this.reminder = reminder
      }
    },
    deleteReminder(reminder){
      axios.delete(`/reminders/${reminder.id}`).then(response=>{
        this.$toasted.success('Notificación eliminada exitosamente')
        this.reminders = this.reminders.filter( r => reminder.id != r.id )
        this.reminder = {}
      },error=>{
        console.log(error);
      })
    },
    fetchExtensions(){
      this.loader = this.$loading.show({isFullPage:false, parent: this.$refs.remindersList})
      axios.get('extensions/list').then(response=>{
        this.extensions = response.data.data
        this.extensions.unshift({'name':'TODOS', 'id':0})
      },error=>{
        console.log(error.response.data);
      }).then(()=>{
        this.loader.isActive = false
      })
    },
    fetchReminders(byExtension = false){
      this.loadingReminders = true
      let url = 'reminders/list/'
      if( byExtension ){
        url += `?extension=${this.extension.id}`
      }
      console.log( url );
      axios.get(url).then(response=>{
        this.reminders = response.data.data
      },error=>{
        console.log(error.response.data);
      }).then(()=>{
        this.loadingReminders = false
      })
    }
  },
  mounted(){
    this.fetchExtensions()
  }
}
</script>

<style lang="css" scoped>
</style>
