<!DOCTYPE html>
<html>

<head>
  <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" type="text/css">
  <link href="https://cdn.jsdelivr.net/npm/quasar@2.11.2/dist/quasar.prod.css" rel="stylesheet" type="text/css">
  <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
  <style>
    .phenlinea-navbar {
      border-radius: 10px;
      background: #fff;
      padding: 10px 20px;
      margin: 10px auto 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
      position: relative;
    }

    .phenlinea-navbar__title {
      color: rgba(0, 0, 0, 87%);
      font-size: 19px;
      font-weight: 400;
      letter-spacing: .015em;
      display: inline-flex;
      align-items: center;
    }

    .phenlinea-navbar__brand {
      max-height: 50px;
      position: 50%;
      transform: translateX(-50%);
    }

    .phenlinea-navbar__actions {
      max-height: 50px;
    }
  </style>
</head>

<body>
  <div id="app">
    <div id="q-app">
      @include('layouts.navbar.new', ['title'=>$title])
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