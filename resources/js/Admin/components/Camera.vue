<template>
  <div id="camera">
    <video id="video" ref="video" @canplay="setDimensions"/>
    <div class="camera-actions">
      <button
        type="button"
        class="btn btn-secondary me-4"
        @click="$emit('cameraClosed')">
        Cancelar
      </button>
      <button
        type="button"
        class="btn btn-primary"
        @click="takePicture">
        <i class="material-symbols-outlined">camera</i> Capturar 
    </button>
    </div>
    <canvas v-show="false" ref="canvas"></canvas>
  </div>
</template>

<script>
export default {
  name: "Camera",
  data(){return {
    width: 640,
    height: 0
  }},
  props: {
    fullscreen: Boolean,
  },
  methods: {
    startVideo() {
      navigator.getMedia =
        navigator.getUserMedia ||
        navigator.msGetUserMedia ||
        navigator.mozGetUserMedia ||
        navigator.webkitGetUserMedia;

      if (!navigator.getMedia) return;
      let that = this;

      navigator.getMedia(
        { video: true, audio: false },
        function (stream) {
          that.$refs.video.srcObject = stream;
          that.$refs.video.play();
        },
        function (err) {
          console.log(err);
        }
      );
    },
    setDimensions() {
      this.height = this.$refs.video.videoHeight / (this.$refs.video.videoWidth / this.width)
      this.$refs.video.width = this.width
      this.$refs.video.height = this.height
      this.$refs.canvas.width = this.width
      this.$refs.canvas.height = this.height
    },
    takePicture() {
      this.$refs.canvas.getContext("2d").drawImage(
        this.$refs.video,
        0,
        0,
        this.width,
        this.height
      );
      let that = this
      let data = this.$refs.canvas.toBlob(function(blob){
        that.$emit("pictureTaken", blob);
      })
    },
  },
  mounted() {
    this.startVideo();
  },
};
</script>

<style scoped>
#camera {
  position: fixed;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  z-index: 30000;
  width: 100vw;
  height: 100vh;
  display: flex;
  flex-flow: column nowrap;
  align-items: center;
  background: rgba(0,0,0,.7);
}
#video {
  margin-bottom: 10px;
}
.camera-actions {
  position: fixed;
  bottom: 50px;
}
</style>
