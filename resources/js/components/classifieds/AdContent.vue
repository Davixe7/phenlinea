<template>
  <div class="ad-content">
    <v-card>
      <div v-if="ad.pictures && ad.pictures.length" :id="`adCarousel${id}`" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
          <li :data-target="`#adCarousel${id}`" data-slide-to="0" class="active"></li>
          <li v-if="ad.pictures.length > 1" v-for="n in ad.pictures.length - 1" :data-target="`#adCarousel${id}`" :data-slide-to="n+1"></li>
        </ol>
        <div class="carousel-inner">
          <div
              class="carousel-item"
              v-for="(picture, i) in ad.pictures"
              :class="{active: i == 0}">
            <img class="d-block w-100" :src="'/'+picture.url.replace('pictures', 'thumbnails/300')" :alt="picture.name">
          </div>
        </div>
        <a class="carousel-control-prev" :href="`#adCarousel${id}`" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" :href="`#adCarousel${id}`" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>
      <v-card-title>
        {{ ad.name }}
      </v-card-title>
      <v-card-subtitle>
        <span>
          <b>${{ad.price}}</b> - 
          {{ ad.description.substr(0,ad.description.length / 4) }}
        </span>
      </v-card-subtitle>
    </v-card>
  </div>
</template>

<script>
  export default {
    props: ['ad', 'id'],
    name: 'AdContent',
    data(){return{}}
  }
</script>

<style lang="scss">
.ad-content {
  margin-bottom: 15px;
  .placeholder {
    background: #efefef;
    border-radius: 4px;
    margin-bottom: 10px;
    height: 150px;
  }
  h4 {
    font-size: 1.25em;
    margin-bottom: 0;
    color: #000;
    font-weight: 600;
  }
  .carousel {
    margin-bottom: 15px;
  }
}
</style>