<template>
  <div class="workday-scheduler" v-if="name">
    <div class="form-check d-block">
      <input type="checkbox" id="works-today" class="form-check-input" @change="update" v-model="works">
      <label class="form-check-label" for="works-today">Trabaja este día</label>
    </div>
    
    <div class="d-inline-block">
      <label>Apertura</label>
      <HourMinClock v-model="open" :disabled="!works" @input="update"/>
    </div>
    <div class="d-inline-block">
      <label>Cierre</label>
      <HourMinClock v-model="close" :disabled="!works" @input="update"/>
    </div>
    
  </div>
</template>

<script>
import HourMinClock from './HourMinClock.vue'
export default {
  components: { HourMinClock },
  props: { workday: Object },
  data(){return {
    open: 0,
    close: 0,
    name: '',
    works: false
  }},
  methods:{
    update(){
      let openmins  = (this.open % 60) < 10 ? '0' + (this.open % 60) : (this.open % 60)
      let closemins = (this.close % 60) < 10 ? '0' + (this.close % 60) : (this.close % 60)
      let openhours = Math.floor(this.open/60) < 10 ? '0' + Math.floor(this.open/60) : Math.floor(this.open/60)
      let closehours = Math.floor(this.close/60) < 10 ? '0' + Math.floor(this.close/60) : Math.floor(this.close/60)
      
      this.$emit('workdayUpdated', {
        'name': this.name,
        'works': this.works,
        'open': this.open,
        'close': this.close,
        '_open':  openhours  + ':' + openmins,
        '_close': closehours + ':' + closemins
      })
    }
  },
  watch:{
    workday(newVal){
      this.name  = newVal.name
      this.works = newVal.works
      this.open  = newVal.open
      this.close = newVal.close
    }
  }
}
</script>

<style lang="sass" scoped>
  label
    display: block
</style>
