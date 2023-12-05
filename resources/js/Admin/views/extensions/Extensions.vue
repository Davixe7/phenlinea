<template>
  <div id="extensions">
    <div id="extensions-table-wrap" class="table-responsive pb-0">

      <div class="table-header d-flex align-items-center pe-2 py-2">
        <SearchForm :collection="extensions" v-show="extensions && extensions.length" v-model="results">
        </SearchForm>

        <a target="_blank" href="extensions/export" class="ms-3 btn btn-sm btn-outline btn-outline-success btn-round">
          <i class="material-symbols-outlined">
            export_notes
          </i>
        </a>
      </div>

      <table class="table" v-if="results && results.length">
        <thead>
          <th>Sync</th>
          <th>Apto.</th>
          <th>Mascotas</th>
          <th>Vehículos</th>
          <th>Útil</th>
          <th>Tel. propietario</th>
          <th>Citofonía 1</th>
          <th>Citofonía 2</th>
          <th class="text-right">Detalle</th>
        </thead>
        <tbody>
          <tr v-for="extension in results" :key="extension.id">
            <td>
              <i
                class="material-symbols-outlined"
                :class="{'text-success':extension.device_room_id || extension.device_synced, 'text-danger':!extension.device_room_id && !extension.device_synced}">
                {{ (extension.device_room_id || extension.device_synced) ? 'cloud_done' : 'cloud_off' }}
              </i>
            </td>
            <td>{{ extension.name }}</td>
            <td>{{ extension.pets_count }}</td>
            <td>{{ extension.vehicles ? extension.vehicles.length : 0 }}</td>
            <td>{{ (extension.has_deposit) ? 'SÍ' : 'NO' }}</td>
            <td>{{ extension.owner_phone }}</td>
            <td>{{ extension.phone_1 }}</td>
            <td>{{ extension.phone_2 }}</td>
            <td>
              <a :href="`extensions/${extension.id}/visitors`">
                <i class="material-symbols-outlined">lock_open</i>
              </a>
              <a :href="`extensions/${extension.id}/edit`">
                <i class="material-symbols-outlined">visibility</i>
              </a>
              <a href="#" @click="deleteExtension(extension.id)">
                <i class="material-symbols-outlined">delete</i>
              </a>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="alert alert-info" v-else>
        No hay extensiones disponibles para mostrar
      </div>
    </div>

    <div class="fab-container">
      <a href="extensions/create" class="btn btn-primary btn-fab">
        <i class="material-symbols-outlined">add</i>
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

  axios.delete(`extensions/${id}`)
    .then(() => {
      extensions.value = extensions.value.filter(extension => extension.id != id)
      results.value = [...extensions.value]
    })
    .catch(error => console.log(error.response.data))
}

onMounted(() => {
  extensions.value = [...props.items]
  results.value = [...extensions.value]
})
</script>

<style>
.table-header div:first-child {
  flex: 1 0 auto;
}

.table-header div:first-child input {
  width: 100%;
}

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


