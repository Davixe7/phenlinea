<template>
  <div class="row">
    <button class="fab" @click="importing = !importing">
      <i class="material-symbols-outlined">add</i>
    </button>
    <div class="col">
      <div class="table-responsive">
        <h1>
          Facturas del mes
        </h1>

        <div class="container-fluid">
          <form @submit.prevent="fetchInvoices">
            <div class="row">
              <div class="col mb-2">
                <select name="month" class="form-control" v-model="month">
                  <option v-for="(monthName, i) in monthsName" :value="i + 1">
                    {{ monthName }}
                  </option>
                </select>
              </div>
              <div class="col">
                <select name="year" v-model="year" class="form-control mb-2">
                  <option value="2022">2022</option>
                  <option value="2023">2023</option>
                </select>
              </div>
              <div class="col mb-2">
                <button type="submit" class="btn btn-secondary w-100 justify-content-center">
                  Consultar
                </button>
              </div>
            </div>
          </form>

          <input
            v-model="search"
            type="search"
            class="form-control"
            placeholder="Buscar por nombre de unidad"
          />
        </div>

        <table class="table" v-if="results && results.length">
          <thead>
            <th>Numero</th>
            <th>NIT</th>
            <th>Unidad</th>
            <th>Estado</th>
            <th>Total</th>
            <th>Pagado el</th>
            <th>Accion</th>
          </thead>
          <tbody>
            <tr v-for="invoice in results" :key="invoice.id">
              <td>
                {{ invoice.number }}
              </td>
              <td>
                {{ invoice.admin.nit }}
              </td>
              <td>
                {{ invoice.admin.name }}
              </td>
              <td>
                <div class="pill"
                  :class="{ 'pill-pendiente': invoice.status == 'pendiente', 'pill-pagado': invoice.status == 'pagado' }">
                  {{ invoice.status }}
                </div>
              </td>
              <td>
                {{ invoice.total }}
              </td>
              <td>
                {{ invoice.paid_at }}
              </td>
              <td>
                <select :disabled="(invoice.payment_method == 'pse' && invoice.status == 'pagado')"
                  v-model="invoice.status" @change="updateInvoice(invoice)" class="form-control form-control-sm">
                  <option :value="'pagado'">Pagado</option>
                  <option :value="'pendiente'">Pendiente</option>
                </select>
              </td>
            </tr>
          </tbody>
        </table>
        <div v-else class="alert alert-info text-center">
          No hay resultados disponibles
        </div>
      </div>
    </div>

    <div class="col" :class="{ 'd-none': !importing }">
      <ul class="list-group px-0">
        <li class="list-group-item">
          <h6>
            Cargar XLSX
          </h6>
          <form
            @submit.prevent="uploadInvoices"
            action="#"
            method="POST"
            enctype="multipart/form-data">
            <div class="row">
              <div class="col">
                <div class="form-group mb-2">
                  <label for="month" class="mb-2">Mes</label>
                  <select name="month" class="form-control" v-model="month" required>
                    <option
                      v-for="(monthName, i) in monthsName" :value="i + 1">
                      {{ monthName }}
                    </option>
                  </select>
                </div>
              </div>
              <div class="col">
                <div class="form-group mb-2">
                  <label for="year" class="mb-2">Año</label>
                  <select v-model="year" class="form-control" required>
                    <option value="2022">2022</option>
                    <option value="2023">2023</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="#">Archivo Excel</label>
              <input ref="attachmentInput" type="file" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 justify-content-center" :disabled="uploading">
              {{ !uploading ? 'Importar facturas' : 'Cargando...'}}
            </button>
          </form>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import {ref, onMounted, computed} from 'vue'
const props = defineProps(['monthsName', 'rows'])

const search   = ref('')
const invoices = ref([])

const attachmentInput = ref(null)
const importing = ref(false)
const month = ref('')
const year = ref('')
const uploading = ref(false)

const results  = computed(()=>{
  if( search.value == '' ) return [...invoices.value]
  return invoices.value.filter(invoice => invoice.admin.name.toLowerCase().includes( search.value.toLowerCase() ))
})

function updateInvoice(invoice) {
  if (!window.confirm('seguro que desea actualizar el estado de la factura?')) return
  axios.post(`/admin/invoices/${invoice.id}`, { status: invoice.status, '_method': 'PUT' })
  .then(response => invoices.value.splice(invoices.value.indexOf(invoice), 1, response.data.data))
}

function fetchInvoices() {
  let data = { year: year.value, month: month.value }
  axios.get('/admin/invoices/upload', { params: data })
  .then(response => invoices.value = Object.values(response.data.data))
}

function uploadInvoices(){
  if( !month.value || !year.value  ) return
  if( !attachmentInput.value.files.length ) return

  let data = new FormData()
  data.append('year', year.value)
  data.append('month', month.value)
  data.append('file', attachmentInput.value.files[0])

  axios.post('/admin/invoices/import', data)
  .then(response => {
    attachmentInput.value = ''
    fetchInvoices()
  })
  .catch(error => console.log(error))
}

onMounted(()=>{
  invoices.value = [...props.rows]

  let date = new Date()
  year.value = date.getFullYear()
  month.value = date.getMonth() + 1
})
</script>

<style>
input[type=search].form-control {
  padding: 0.375rem 0.75rem !important;
}
</style>