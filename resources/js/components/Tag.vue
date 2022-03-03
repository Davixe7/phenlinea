<template>
  <span
    class="tag"
    :class="{
      'tag-default' : config[status].default,
      'tag-success' : config[status].success,
      'tag-warning' : config[status].warning,
      'tag-danger'  : config[status].error}"
    >
    {{ config[status].text }} 
  </span>
</template> 

<script>
export default {
  name: 'Tag', 
  props: {
    status: {type: String, default: 'pending'},
    config: {type: Object, default: ()=>{return{
      pending: {
        default: true,
        text: 'pendiente'
      },
      approved: {
        success: true,
        text: 'aprobada'
      },
      denied: {
        error: true,
        text: 'denegada'
      }
    }}
    }
  },
}
</script>

<style lang="sass" scoped>
  .tag
    border-radius: 3px
    text-align: center
    font-weight: 500
    color: #fff
    padding: 2px 5px
</style>
