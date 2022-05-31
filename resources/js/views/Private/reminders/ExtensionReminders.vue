<template>
  <div id="reminders">
    <h1 class="text-center">Notificaciones</h1>
    <hr>
    <div class="row">
      <div class="col-md-6 mx-auto">
        <div class="form-row">
          <span class="col form-section-title">Todas las notificaciones</span>
        </div>
        <RemindersList :loading="loadingReminders" :reminders="reminders" :isAdmin="isAdmin"/>
      </div>
    </div>
  </div>
</template>

<script>
import RemindersList  from './RemindersList.vue'
export default {
  props: {
    reminders: {
      type: Array,
      default: function(){ return [] }
    },
    isAdmin: false
  },
  components: { RemindersList },
  name: 'ExtensionReminders',
  data(){
    return {
      loader: {},
      loadingReminders: false
    }
  },
  methods:{},
  mounted(){}
}
</script>
