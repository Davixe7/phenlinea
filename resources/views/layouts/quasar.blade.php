<!DOCTYPE html>
<html>

<head>
  <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" type="text/css">
  <link href="https://cdn.jsdelivr.net/npm/quasar@2.11.2/dist/quasar.prod.css" rel="stylesheet" type="text/css">
</head>

<body>
  <div id="q-app">
    <div style="background: #4b7094" class="q-px-md q-px-sm">
      <q-toolbar class="text-white">
        <q-toolbar-title class="text-white" style="max-width: fit-content;">PHenlínea</q-toolbar-title>

        <q-space></q-space>

        <q-btn flat stretch href="/extensions" label="Extensiones">
        </q-btn>
        <q-separator dark vertical></q-separator>

        <q-btn flat stretch href="{{ route('pqrs.index') }}" label="PQRS">
        </q-btn>
        <q-separator dark vertical></q-separator>

        <q-btn flat stretch href="{{ route('novelties.index') }}" label="Novedades">
        </q-btn>
        <q-separator dark vertical></q-separator>

        <q-btn flat stretch href="{{ route('visits.index') }}" label="Visitas">
        </q-btn>
        <q-separator dark vertical></q-separator>

        <q-btn flat stretch href="{{ route('invoices.index') }}" label="Facturas PSE">
        </q-btn>
        <q-separator dark vertical></q-separator>

        <q-btn flat stretch href="{{ route('whatsapp.index') }}" label="Mensajes de WhatsApp">
        </q-btn>

        <q-space></q-space>

        <q-btn flat @click="drawerRight = !drawerRight" round dense>
          <q-icon class="material-symbols-outlined">menu</q-icon>
        </q-btn>
      </q-toolbar>
    </div>
    @yield('content')
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/vue@3/dist/vue.global.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/quasar@2.11.4/dist/quasar.umd.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/quasar@2.11.4/dist/lang/es.umd.prod.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/quasar@2.11.4/dist/icon-set/material-symbols-outlined.umd.prod.js"></script>
  @yield('script')
</body>

</html>