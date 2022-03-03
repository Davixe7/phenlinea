<template>
  <div class="hour-min-clock clock-wrapper">
    <input type="tel" class="form-control" ref="hourInput"   max="23" min="0"  @change="update" v-model.number="hours" :disabled="disabled">
    <input type="tel" class="form-control" ref="minuteInput" max="59" min="0"  @change="update" v-model.number="mins"  :disabled="disabled">
  </div>
</template>

<script>
export default {
  name: 'HourMinClock',
  props: {
    value: {
      type:Number, default: 0
    },
    disabled: {
      type: Boolean,
      default: false
    }
  },
  watch:{
    value(){
      let hours = Math.floor( this.value / 60 )
      let mins  = Math.round( this.value % 60 )
      this.hours = hours
      this.mins  = mins
    }
  },
  data(){return {
    hours: 0,
    mins: 0
  }},
  computed:{
    alltime(){
      return (this.hours * 60 + this.mins)
    }
  },
  methods:{
    update(){
      this.$emit('input', this.alltime)
    }
  }
}
</script>

<style lang="sass" scoped>
  .hour-min-clock
    width: 100px
    display: inline-flex
    margin-right: 20px
    input.form-control
      font-weight: 500
      font-size: 1.25em
      text-align: center
    input.form-control:first-child
      border-top-right-radius: 0
      border-bottom-right-radius: 0
    input.form-control:last-child
      border-top-left-radius: 0
      border-bottom-left-radius: 0
      margin-left: -2px
</style>
