<template>
  <div class="week-scheduler">
    <div v-for="(d,i) in workdays" :key="d.name"
      class="form-check form-check-inline">  
      <input type="radio" class="form-check-input" :value="d" :id="i" v-model="workday">
      <label :for="i" class="form-check-label">{{ d.name }}</label>
    </div>
    <div class="w-100">
      <WorkdayScheduler
        :workday="workday"
        @workdayUpdated="updateWorkday"
      />
    </div>
  </div>
</template>

<script>
export default {
  props: ['schedule'],
  data(){return{
    workdays: [],
    workday: {}
  }},
  methods:{
    updateWorkday(fresh){
      this.workday = fresh
      if( fresh.name == 'todos' ){
        this.globalWorkday  = fresh
      }
      this.workdays = this.workdays.map(function(i){
        if( fresh.name == 'todos' || i.name == fresh.name){
          return {'name':  i.name, 'open':  fresh.open, 'close': fresh.close, '_open': fresh._open, '_close': fresh._close, 'works': fresh.works}
        }
        return i
      })
      this.$emit('input', this.workdays)
    },
  },
  mounted(){
    if(this.schedule && this.schedule.length){
      this.workdays = this.schedule
      return
    }
    let days = ['Lun', 'Mar', 'Mie','Jue', 'Vie', 'Sab', 'Dom']
    days.forEach(d=>this.workdays.push({ name: d, open: 0, close: 0, works: true })) 
    this.workday = this.workdays[0]
    this.$emit('input', this.workdays)
  }
}
</script>

<style lang="css" scoped>
</style>