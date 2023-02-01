@extends('layouts.quasar')
@section('content')
<div class="q-pa-lg">
  <div class="row">
    <div class="col">
      <q-card class="q-mb-md">
        <q-card-section>
          <div class="row">
            <div class="col-6">
              <div class="row">
                <div class="col">
                  <div class="stat-number">@{{ rows.length }}</div>
                  <div>Total</div>
                </div>
                <div class="col">
                  <div class="stat-number">@{{ rows.filter(f=>f.read_at == null).length }}</div>
                  <div>Sin leer</div>
                </div>
                <div class="col">
                  <div class="stat-number">@{{ rows.filter(f=>f.replied_at == null).length }}</div>
                  <div>Sin responder</div>
                </div>
              </div>
            </div>
            <div class="col-6 flex justify-end align-items-center">
              <q-btn href="https://phenlinea.com/pqrs/qr/?date={{ now() }}" flat icon="sym_o_qr_code_2" label="Descargar QR">
              </q-btn>
            </div>
          </div>
        </q-card-section>
      </q-card>
      <q-table title="PQRS" :rows="results" :columns="columns" :filter="search" row-key="name" :pagination="{rowsPerPage: 0}">
        <template v-slot:top-right>
          <q-input debounce="300" v-model="search" placeholder="Buscar"></q-input>

          <div class="q-mr-md">
            Filtrar por:
          </div>
          <q-select :options="filters" v-model="filter"></q-select>
        </template>
        <template v-slot:body-cell-created_at="props">
          <q-td :props="props">
            @{{ new Date(props.value).toLocaleString() }}
          </q-td>
        </template>
        <template v-slot:body-cell-read_at="props">
          <q-td :props="props">
            @{{ props.value ? new Date(props.value).toLocaleString() : 'pendiente' }}
          </q-td>
        </template>
        <template v-slot:body-cell-replied_at="props">
          <q-td :props="props">
            @{{ props.value ? new Date(props.value).toLocaleString() : 'pendiente' }}
          </q-td>
        </template>
        <template v-slot:body-cell-action="props">
          <q-td :props="props">
            <q-btn flat round @click="markAsRead(props.row)">
              <q-icon class="material-symbols-outlined">remove_red_eye</q-icon>
            </q-btn>
          </q-td>
        </template>
      </q-table>
    </div>
    <div class="col-4 q-px-md" v-if="currentPqrs">
      <q-card class="q-mb-md">
        <q-card-section style="height: 80px; font-size: 1.25rem; display: flex; align-items: center;">
          Detalles de PQRS
        </q-card-section>
        <q-card-section>
          <div class="flex justify-between">
            <div class="text-medium">
              Nombre
            </div>
            <div>
              @{{ currentPqrs.name }}
            </div>
          </div>
          <div class="flex justify-between">
            <div class="text-medium">
              Teléfono
            </div>
            <div>
              @{{ currentPqrs.phone }}
            </div>
          </div>
          <div class="flex justify-between">
            <div class="text-medium">
              Descripción
            </div>
            <div>
              @{{ currentPqrs.description }}
            </div>
          </div>
          <div>
            <q-separator class="q-my-md"></q-separator>
            <div class="flex justify-between q-mb-sm">
              <q-badge color="grey">Creado el</q-badge>
              <div>@{{ new Date(currentPqrs.created_at).toLocaleString() }}</div>
            </div>
            <div class="flex justify-between q-mb-sm">
              <q-badge color="blue">Leído</q-badge>
              <div>@{{ currentPqrs.read_at ? new Date(currentPqrs.read_at).toLocaleString() : 'pendiente' }}</div>
            </div>
            <div class="flex justify-between q-mb-sm">
              <q-badge color="green">Respuesta enviada</q-badge>
              <div>@{{ currentPqrs.replied_at ? new Date(currentPqrs.replied_at).toLocaleString() : 'pendiente' }}</div>
            </div>
          </div>

          <div v-if="currentPqrs" class="flex">
            <q-avatar v-for="(attachment, i) in currentPqrs.attachments" :class="q-pe-2" @click="showImg(i)">
              <img :src="attachment">
            </q-avatar>
          </div>

          <q-input autogrow type="textarea" v-model="currentPqrs.answer" label="Respuesta"></q-input>
          <div class="flex justify-end">
            <q-btn :disabled="!currentPqrs" @click="reply" flat icon="sym_o_send">
            </q-btn>
          </div>
        </q-card-section>
      </q-card>
    </div>
  </div>

  <vue-easy-lightbox v-if="currentPqrs" :visible="visibleRef" :imgs="currentPqrs.attachments" :index="indexRef" @hide="onHide">
  </vue-easy-lightbox>
</div>
@endsection

@section('script')
<script src="https://unpkg.com/vue-easy-lightbox@next/dist/vue-easy-lightbox.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<style>
  .stat-number {
    font-size: 1.5rem;
  }
</style>
<script>
  const app = Vue.createApp({
    setup() {
      const search = Vue.ref('')
      const currentPqrs = Vue.ref(null)

      const rows = Vue.ref([])
      const filters = Vue.ref([{
          value: 'read_at',
          label: 'No leídos'
        },
        {
          value: 'replied_at',
          label: 'Sin responder'
        },
        {
          value: 'all',
          label: 'Todos'
        },
      ])

      const filter = Vue.ref({
        value: 'all',
        label: 'Todos'
      })

      const results = Vue.computed(() => {
        if (filter.value.value == 'all') return [...rows.value]
        return rows.value.filter(f => f[filter.value.value] == null)
      })

      function markAsRead(pqrs) {
        currentPqrs.value = pqrs
        if (pqrs.read_at != null) return
        axios.post('https://phenlinea.com/pqrs/' + currentPqrs.value.id + '/markAsRead', {
            _method: 'PUT'
          })
          .then(response => {
            rows.value.splice(rows.value.indexOf(pqrs), 1, response.data.data)
          })
      }

      function reply() {
        let data = {
          ...currentPqrs.value,
          _method: 'PUT'
        }
        axios.post('https://phenlinea.com/pqrs/' + currentPqrs.value.id, data)
          .then(response => {
            rows.value.splice(rows.value.indexOf(currentPqrs.value), 1, response.data.data)
            Quasar.Notify.create('Respuesta enviada')
          })
      }

      Vue.onMounted(() => {
        axios.get('/pqrs').then(response => rows.value = response.data.data)
      })

      //Lightbox configuration
      const visibleRef = Vue.ref(false)
      const indexRef = Vue.ref(0)
      const showImg = (index) => {
        indexRef.value = index
        visibleRef.value = true
      }
      const onHide = () => visibleRef.value = false

      return {
        visibleRef,
        indexRef,
        showImg,
        onHide,

        search,
        rows,
        results,
        filters,
        filter,
        currentPqrs,
        markAsRead,
        reply,
        columns: [{
            align: 'left',
            name: 'id',
            label: 'Código',
            field: 'id',
            sortable: true
          },
          {
            align: 'left',
            name: 'created_at',
            label: 'Creado',
            field: 'created_at',
            sortable: true
          },
          {
            align: 'left',
            name: 'read_at',
            label: 'Leído',
            field: 'read_at',
            sortable: true
          },
          {
            align: 'left',
            name: 'replied_at',
            label: 'Respuesta enviada',
            field: 'replied_at',
            sortable: true
          },
          {
            align: 'left',
            name: 'name',
            label: 'Nombres',
            field: 'name',
            sortable: true
          },
          {
            align: 'left',
            name: 'phone',
            label: 'Teléfono',
            field: 'phone',
            sortable: true
          },
          {
            align: 'right',
            name: 'action',
            label: 'Acciones'
          },
        ],
      }
    },
  })

  app.use(Quasar)
  Quasar.lang.set(Quasar.lang.es)
  Quasar.iconSet.set(Quasar.iconSet.materialSymbolsOutlined)

  app.use(VueEasyLightbox)
  app.mount('#q-app')
</script>
@endsection