<template>
  <div id="reminders-list">
    <div class="reminders-list" v-if="reminders && reminders.length">
      <div class="default-content" v-for="reminder in reminders" :key="reminder.id">
        <div class="content-header">
          <div>
            <h5 class="title">{{ reminder.title }}</h5>
            <span class="date">{{ reminder.created_at }}</span>
          </div>
          <div v-if="isAdmin" class="options-dropdown ml-auto">
            <div class="dropdown">
              <span class="dropdown-toggler" data-toggle="dropdown">
                <i class="material-icons">more_vert</i>
              </span>
              <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="#" @click.prevent="editReminder(reminder)">Editar</a>
                <a class="dropdown-item" href="#" @click.prevent="deleteReminder(reminder)">Eliminar</a>
              </div>
            </div>
          </div>
        </div>
        <span class="description">
          {{ reminder.description }}
        </span>
        
        <div v-if="reminder.pictures && reminder.pictures.length" class="pictures-list">
          <div
            v-for="(picture, _index) in reminder.pictures"
            class="picture-wrapper"
            @click="pictures=reminder.pictures; index=_index">
            <img :src="picture.url">
          </div>
          <CoolLightBox :items="lbPictures" :index="index" @close="index=null"/>
        </div>
        
      </div>
    </div>
    <div v-else class="alert alert-info">
      <i class="material-icons">info</i> No hay notificaciones para mostrar
    </div>
    <div ref="loaderContainer" v-show="loading" style="width: 100%; height: 50px"></div>
  </div>
</template>

<script>
import CoolLightBox from 'vue-cool-lightbox'
export default {
  components: { CoolLightBox },
  name: 'ReminderList',
  props: {
    loading: false,
    reminders: {
      type: Array,
      default: ()=>{[]}
    },
    isAdmin: false
  },
  data(){
    return {
      pictures: [],
      index: null,
      loader: null
    }
  },
  computed:{
    lbPictures(){
      return this.pictures.map(p=>p.url)
    }
  },
  methods:{
    editReminder(reminder){
      this.$emit('editReminder', reminder)
    },
    deleteReminder(reminder){
      if( confirm('¿Seguro que quiere eliminar la notificación?') ){
        this.$emit('deleteReminder', reminder)
      }
    }
  },
  watch:{
    loading(newVal){
      if( newVal ){
        this.loader = this.$loading.show({
          container:this.$refs.loaderContainer,
          'is-full-page':false,
          'width': 40,
          'height': 40,
        })
      }else{
        this.loader.isActive = false
      }
    },
  }
}
</script>

<style lang="sass" scoped>
  .pictures-list
    display: flex
    flex-flow: row nowrap
  .picture-wrapper
    width: 120px
    overflow: hidden
    margin-right: 10px
    border-radius: 3px
    box-shadow: 0 1px 3px 1px rgba(0,0,0,.2)
    img
      height: 130px
      width: auto
</style>
