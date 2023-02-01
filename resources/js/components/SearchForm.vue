<template>
  <div class="search-form">
    <form autocomplete="off">
      <!-- <v-text-field
        prepend-inner-icon="search"
        v-model="query"
        outlined
        clearable
        dense
        placeholder="Buscar..."
        hide-details="auto"/> -->
      <input autocomplete="none" type="search" v-model="query" placeholder="Buscar...">
    </form>
  </div>
</template>

<script>
export default {
  name: 'SearchForm',
  props: ['collection', 'attribute'],
  data(){
    return {
      query: ''
    }
  },
  watch:{
    results(){
      this.$emit('input', this.results)
    }
  },
  computed:{
    results(){
      if( this.collection.length && this.query ){
        let attribute = (this.attribute) ? this.attribute : 'name'
        
        return this.collection.filter( f => {
          if( f.hasOwnProperty( attribute ) ){
            return f[attribute].toLowerCase().includes( this.query.toLowerCase() )
          }
          if( typeof f === 'string' ){
            return f.toLowerCase().includes( this.query.toLowerCase() )
          }
          return false
        })
      }
      
      return [...this.collection]
    }
  }
}
</script>

<style lang="css" scoped>
  .svg-icon {
    position: absolute;
    z-index: 20;
    left: 13px;
    top: 10px;
    width: 17px;
    height: 17px;
  }
  .search-form {
    position: relative;
    margin-left: auto;
  }
  .search-form input {
    font-size: 1em;
    border-radius: 20px;
    padding: 7px 20px 7px 35px;
    border: 1px solid #d4e2e6;
    box-shadow: none;
  }
</style>
