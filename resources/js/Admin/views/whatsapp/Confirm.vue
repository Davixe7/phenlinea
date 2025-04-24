<script setup>
</script>

<template>
  <div style="height: 100%;">
    <template>
      <div class="d-flex flex-column align-items-center justify-content-center"
        style="height: 100%; background: radial-gradient(#36cbf2b8, #36ecf21c, transparent);">
        <div>
          <img src="/img/sent.png">
        </div>
        <div class="mb-2" style="font-weight: 700; font-size: 18px;">
          ¡Listo!
        </div>
        <div class="mb-2">
          El envío de su mensaje empezara en breve.
        </div>
        <a href="/home" class="btn btn-primary">
          Volver al menú
        </a>
      </div>
    </template>
  </div>
</template>

<style lang="scss">
.instance-id {
  padding: 1rem;
  text-align: center;
  border: 1px solid rgba(0, 0, 0, .7);
}
.pairing-code {
  font-size: 1.25rem;
  font-family: 'Monospace';
  letter-spacing: .4rem;
  padding: 1rem;
  text-align: center;
  border: 1px solid rgba(0, 0, 0, .7);
}
</style>
