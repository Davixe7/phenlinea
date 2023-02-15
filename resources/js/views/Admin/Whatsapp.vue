<template>
  <div class="container pt-4">
    <div class="row">
      <div class="col-lg-3">
        <div class="card">
          <div class="card-header">
            Seleccionar destinatarios
            <div>
              <i>
                Seleccionados {{ receivers.length }}
              </i>
            </div>
          </div>
          <div class="card-body p-0" style="max-height: calc(100vh - 200px); overflow: auto;">

            <input id="extensionsFilter" v-model="search" placeholder="Buscar...">

            <ul class="list-group p-0">
              <li class="list-group-item">
                <button class="btn btn-link w-100 justify-content-center" @click="selectAll">
                  Seleccionar todos
                </button>
              </li>
              <li class="list-group-item">
                <input type="checkbox" v-model="ownersOnly" class="mr-3">
                <label for="checkbox-owners_only">Solo propietarios</label>
              </li>
              <li v-for="extension in results" class="list-group-item">
                <input v-model="receivers" :value="extension.id" type="checkbox" id="`checkbox-${extension.id}`"
                  class="mr-3 extension-check">
                <label for="`checkbox-${extension.id}`">
                  {{ extension.name }}
                </label>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="table-responsive mb-4">

          <textarea id="message" placeholder="Escribe un mensaje" rows="10" v-model="message" class="form-control mb-3"
            required>
          </textarea>

          <div style="margin-bottom: -22px;" class="px-3">
            <div class="attachment-alert mb-2">
              Adjuntar solo Imagenes Y PDF
            </div>
            <div class="d-flex align-items-center">
              <input type="file" class="form-control d-none" ref="attachmentInput" @change="updateAttachment">
              <button type="button" class="btn-round btn-attachment mr-3" @click="openFileDialog()">
              </button>
              <div class="attachmentDetails">
                {{ attachment? attachment.name : 'ningún archivo seleccionado' }}
              </div>
              <button type="button" class="btn btn-primary ms-auto" @click="send()">
                Enviar
              </button>
            </div>
          </div>
        </div>

        <div v-if="history && history.length" class="table-responsive">
          <table class="table">
            <thead>
              <th>
                Mensaje
              </th>
              <th>
                Cant.
              </th>
              <th>
                Fecha
              </th>
            </thead>
            <tbody>
              <tr v-for="batch in history">
                <td>
                  {{ batch.message }}
                </td>
                <td>
                  {{ batch.receivers_numbers.split(',').length }}
                </td>
                <td>
                  {{ new Date(batch.created_at).toLocaleString('es-CO', { timezone: 'America/Colombia' }) }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="col-lg-3" style="text-align: right">
        <div class="card mb-3">
          <div class="card-body">
            <div class="monospace">
              status
            </div>
            <div class="d-flex align-items-center justify-content-end">
              <span class="lightbulb"></span> Online
            </div>
            <div class="monospace">
              {{ whatsappInstanceId }}
            </div>
            <a :href="logoutRoute" class="btn btn-danger">
              Cerrar sesión
            </a>
          </div>
        </div>

        <div class="card mb-3">
          <div class="card-header">
            <i class="material-icons">info</i> Recomendación
          </div>
          <div class="card-body">
            PHenlínea SAS recomienda el uso responsable del servicio de mensajería masíva
          </div>
        </div>

        <div class="card background-info">
          <div class="card-header">
            <i class="material-icons">info</i> Advertencia
          </div>
          <div class="card-body">
            PHenlínea SAS no se hace responsable del uso inapropiado del servicio de mensajería masíva
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue';

const props = defineProps(['extensions', 'logoutRoute', 'history', 'whatsappInstanceId'])
const attachmentInput = ref(null)
const ownersOnly = ref(false)
const search = ref('')
const message = ref('')
const receivers = ref([])
const attachment = ref(null)

const results = computed(() => {
  let results = [...props.extensions]
  if (ownersOnly.value) { results = results.filter(extension => (extension.owner_phone ? true : false)) }
  if (search.value == '') return results
  let searchText = search.value.toLowerCase().trim()
  return props.extensions.filter(extension => extension.name.toLowerCase().trim().includes(searchText))
})

function openFileDialog() {
  attachmentInput.value.click()
}

function updateAttachment() {
  if (attachmentInput.value.files.length) {
    attachment.value = attachmentInput.value.files[0]
    return
  }
  attachment.value = null
}

function send() {
  if( !window.confirm('Seguro que desea enviar el mensaje?') ) return
  if (!receivers.value.length) { alert('Debe incluir al menos un destinatario'); return; }
  if (!message.value) { alert('Debe incluir un mensaje'); return; }

  let data = new FormData()
  data.append('message', message.value)
  receivers.value.forEach(receiver => data.append('receivers[]', receiver))

  if (attachment.value) {
    data.append('attachment', attachment.value)
  }

  axios.post('/whatsapp/send', data)
    .then(response => {
      message.value = ''
      receivers.value = []
      attachmentInput.value.value = ''
      props.history.unshift( response.data.data );
      alert('Mensaje enviado exitosamente')
    })
    .catch(error => console.log(error))
}

function selectAll() {
  receivers.value = [...props.extensions].map(extension => extension.id)
}
</script>

<style lang="scss">
.btn-attachment::before {
  content: 'attachment';
  font-family: 'Material Icons';
  font-weight: normal;
  font-style: normal;
  font-size: 24px;
  line-height: 1;
  letter-spacing: normal;
  text-transform: none;
  display: inline-block;
  white-space: nowrap;
  word-wrap: normal;
  direction: ltr;
  -webkit-font-feature-settings: 'liga';
  -webkit-font-smoothing: antialiased;
}
</style>
