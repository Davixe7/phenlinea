@extends('layouts.quasar')
@section('content')
<div class="q-pa-lg">
  <div class="container">
    <div class="row">
      <div class="col">
        <q-table title="Novedades" :rows="rows" :columns="columns" :filter="search" row-key="title" :pagination="{rowsPerPage: 0}">

          <template v-slot:body-cell-created_at="props">
            <q-td :props="props">
              @{{ new Date(props.value).toLocaleString() }}
            </q-td>
          </template>

          <template v-slot:body-cell-action="props">
            <q-td :props="props">
              <q-btn flat round @click="novelty = props.row">
                <q-icon class="material-symbols-outlined">remove_red_eye</q-icon>
              </q-btn>
            </q-td>
          </template>
        </q-table>
      </div>

      <div class="col-4 q-px-md" v-if="novelty">
        <q-card class="q-mb-md">
          <q-card-section style="height: 80px; font-size: 1.25rem; display: flex; align-items: center;">
            Novedad detallada
          </q-card-section>
          <q-card-section>
            <div class="flex justify-between">
              <div class="text-medium">
                Descripción
              </div>
              <div>
                @{{ novelty.description }}
              </div>
            </div>

            <div>
              <q-separator class="q-my-md"></q-separator>
              <div class="flex justify-between q-mb-sm">
                <q-badge color="grey">Creado el</q-badge>
                <div>@{{ new Date(novelty.created_at).toLocaleString() }}</div>
              </div>
            </div>

            <div v-if="novelty" class="flex">
              <q-avatar v-for="(picture, i) in novelty.pictures" :class="q-pe-2" @click="showImg(i)">
                <img :src="picture.url">
              </q-avatar>
            </div>
          </q-card-section>
        </q-card>
      </div>
    </div>
  </div>

  <vue-easy-lightbox v-if="novelty" :visible="visibleRef" :imgs="novelty.pictures" :index="indexRef" @hide="onHide">
  </vue-easy-lightbox>
</div>
@endsection

@section('scripts')
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
      const rows = Vue.ref([])
      const search = Vue.ref('')
      const novelty = Vue.ref(null)

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
        novelty,
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
            name: 'excerpt',
            label: 'Extracto',
            field: 'excerpt',
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
    mounted(){
      axios.get('/novelties').then(response => this.rows = response.data.data)
    }
  })

  app.use(Quasar)
  Quasar.lang.set(Quasar.lang.es)
  Quasar.iconSet.set(Quasar.iconSet.materialSymbolsOutlined)

  app.use(VueEasyLightbox)
  app.mount('#q-app')
</script>
@endsection