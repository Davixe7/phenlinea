<template>
  <div id="store-profile">
    <v-card class="mx-auto store-profile-card">
      <v-img v-if="commerce.profile_picture" height="250" :src="`/${commerce.profile_picture.url}`"></v-img>
      <v-card-title>{{ commerce.name }}</v-card-title>
      <v-card-text>
        <div class="my-4 subtitle-1">$ • {{ commerce.category }}</div>
        <div>{{ commerce.description }}</div>
        <div>
            <small>
                Dirección
            </small>
        </div>
        {{ commerce.address }}
      </v-card-text>

      <v-divider class="mx-4"></v-divider>

      <v-card-title v-if="workday">{{workday.name}} - Abierto hoy</v-card-title>
      <v-card-text v-if="workday">
        <v-chip-group v-if="workday._open && workday._close" active-class="deep-purple accent-4 white--text" column>
          <v-chip>{{ workday._open }}</v-chip>
          <v-chip>{{ workday._close }}</v-chip>
        </v-chip-group>
      </v-card-text>
  </v-card>
  </div>
</template>

<script>
export default {
  props:['commerce'],
  data(){return{
    selection: null,
    workday: null
  }},
  methods:{
    reserve(){}
  },
  mounted(){
    let d = new Date()
    d = d.getDay()
    if( d == 0 ){
      this.workday = this.commerce.schedule[6]
    }else{
      this.workday = this.commerce.schedule[d - 1]
    }
  }
}
</script>

<style lang="css" scoped>
@media(min-width: 991px){
    .store-profile-card {
        max-width: 374px;
    }
}
</style>
