<template>
  <div id="extensions">
    <div class="page-title">
      <h1>Censo del edificio</h1>
    </div>
    <div v-if="results && results.length" id="extensions-table-wrap" class="table-responsive">
      <table class="table">
        <thead>
          <th>Apto.</th>
          <th>Adultos</th>
          <th>Menores</th>
          <th>Mascotas</th>
          <th>Vehículos</th>
          <th>Útil</th>
          <th>Tel. propietario</th>
          <th>Citofonía 1</th>
          <th>Citofonía 2</th>
          <th class="text-right">Detalle</th>
        </thead>
        <tbody>
          <tr v-for="ext in results" :key="ext.id">
            <td>{{ ext.name }}</td>
            <td>{{ ext.adults }}</td>
            <td>{{ ext.minors }}</td>
            <td>{{ ext.pets_count }}</td>
            <td>{{ ext.vehicles ? ext.vehicles.length : 0 }}</td>
            <td>{{ (ext.has_deposit) ? 'SÍ' : 'NO' }}</td>
            <td>{{ ext.owner_phone }}</td>
            <td>{{ ext.phone_1 }}</td>
            <td>{{ ext.phone_2 }}</td>
            <td>
              <a :href="`/extensions/${ext.id}/visitors`">
                <i class="material-icons">lock_open</i>
              </a>
              <a :href="`/extensions/${ext.id}/edit`">
                <i class="material-icons">visibility</i>
              </a>
              <a href="#" @click="deleteExtension(ext.id)">
                <i class="material-icons">delete</i>
              </a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="fab-container">
      <a href="/extensions/create" class="btn btn-primary btn-circle">
        <i class="material-icons">add</i>
      </a>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const extensions = ref([])
const results = ref([])

const props = defineProps({
  items: {
    type: Array,
    default: () => ([])
  }
})

function deleteExtension(id) {
  if (!window.confirm('¿Seguro que quieres eliminar la extensión?')) return

  axios.delete(`/extensions/${id}`)
    .then(() => extensions.value = extensions.value.filter(ext => ext.id != id))
    .catch(error => console.log(error.response.data))
}

onMounted(() => {
  extensions.value = [...props.items]
  results.value = [...extensions.value]
})
</script>

<style>
.extensions-container {
  height: calc(100vh - 112px);
  position: relative;
  padding-bottom: 20px;
}

#extensions-table-wrap {
  height: calc(100% - 50px);
  margin-bottom: 10px;
  overflow: auto;
}

.filled {
  color: #06a906;
}

.empty {
  color: #c0392b;
}

table th:last-child,
table tr td:last-child {
  text-align: right;
}

.btn-export {
  font-size: 1em;
  font-weight: 400;
  color: darkgray;
  text-transform: none;
  border-radius: 5px;
  border: 1px solid lightgray;
  background: #fff;
  box-shadow: none;
  margin-right: 10px;
  height: 40px;
}
</style>

