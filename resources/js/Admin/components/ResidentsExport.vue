<template>
  <div id="residents-export">
    <v-autocomplete
      v-model="admin"
      :items="admins"
      :search-input.sync="search"
      color="white"
      hide-no-data
      hide-selected
      :clearable="true"
      item-text="name"
      item-value="id"
      label="Unidades residenciales"
      prepend-icon="search"
      return-object
    ></v-autocomplete>
    <a :href="url" class="btn btn-success w-100 d-flex align-items-center" :class="{disabled: !this.admin}">
      <span>Descargar Excel</span>
      <i class="material-symbols-outlined ml-auto">arrow_circle_down</i>
    </a>
  </div>
</template>

<script>
export default {
  props: ['admins'],
  name: 'ResidentsExport',
  data(){return{
    admin: null,
    search: ''
  }},
  computed:{
    url(){
      return this.admin ? `/admin/admins/${this.admin.id}/export` : ''
    }
  }
}
</script>

<style lang="css">
  .v-text-field .v-label {
    transform-origin: left;
  }
</style>
