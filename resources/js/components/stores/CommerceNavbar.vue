<template>
  <v-toolbar :color="'#6BA6CD'" :dark="true">
    <v-container class="py-0">
      <v-toolbar-title>
        <v-btn v-if="commerce" icon href="/stores">
          <v-icon>keyboard_backspace</v-icon>
        </v-btn>
        <v-img 
          contain
          v-if="commerce && commerce.logo"
          :src="'/'+commerce.logo.url"
          max-width="125"
          width="125"
          style="display: inline-block; vertical-align: middle;"/>
        <span v-else-if="commerce">
          {{ commerce.name }}
        </span>
        <span v-else><b>Comercios</b></span>
      </v-toolbar-title>
      <v-spacer></v-spacer>
    </v-container>
  </v-toolbar>
</template>

<script>
export default {
  props: ['commerce']
}
</script>

<style lang="css" scoped>
</style>
