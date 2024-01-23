<template>
  <ul class="nav nav-pills nav-fill mb-3">
    <li class="nav-item">
      <a
        class="nav-link"
        :href="`/extensions/${extension.id}/edit`"
        :class="{ disabled: !extension.id, active: page == 'extension' }">
        Apartamento
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" :class="{ disabled: !extension.id, active: page == 'residents' }"
        :href="`/extensions/${extension.id}/residents`">Residentes</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" :class="{ disabled: !extension.id, active: page == 'vehicles' }"
        :href="`/extensions/${extension.id}/vehicles`">Vehículos</a>
    </li>
    <li class="nav-item">
      <a
        class="nav-link"
        :class="{ disabled: !extension.id, active: page == 'invoices' }"
        :href="`/extensions/${extension.id}/balance`">
        Facturación
      </a>
    </li>
  </ul>
</template>

<script setup>
  const props = defineProps(['extension', 'page'])
</script>