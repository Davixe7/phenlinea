<!DOCTYPE html>
<html>

<head>
  <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" type="text/css">
  <link href="https://cdn.jsdelivr.net/npm/quasar@2.11.2/dist/quasar.prod.css" rel="stylesheet" type="text/css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js"></script>
  <style>
        .navbar.bg-dark {
          background: #4b7094 !important;
        }
      .navbar-nav .nav-item a.nav-link {
          padding-left: 1rem;
          padding-right: 1rem;
          border-right: 1px solid #ffffff30 !important;
          font-size: 1em !important;
          color: #fff;
      }
  </style>
</head>

<body>
  <div id="q-app">
    @include('layouts.navbar')
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
