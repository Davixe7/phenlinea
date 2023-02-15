<!DOCTYPE html>
<html>

<head>
  <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" type="text/css">
  <link href="https://cdn.jsdelivr.net/npm/quasar@2.11.2/dist/quasar.prod.css" rel="stylesheet" type="text/css">
  <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
</head>

<body>
  <div id="app">
    <div id="q-app">
      @include('layouts.navbar')
      @yield('content')
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/vue@3/dist/vue.global.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/quasar@2.11.4/dist/quasar.umd.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/quasar@2.11.4/dist/lang/es.umd.prod.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/quasar@2.11.4/dist/icon-set/material-symbols-outlined.umd.prod.js"></script>
  @yield('scripts')
</body>

</html>