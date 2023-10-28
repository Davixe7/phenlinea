<template>
  <button class="btn">
    <v-slot></v-slot>
    <i v-if="icon && !loading" class="material-symbols-outlined">{{ icon }}</i>
    <div class="preloader" v-if="loading"></div>
  </button>
</template>

<script setup>
  const props = defineProps(['loading', 'icon'])
</script>