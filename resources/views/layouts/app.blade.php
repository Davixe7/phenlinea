<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Laravel') }}</title>
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="{{ mix('css/app.css') }}" rel="stylesheet">
  <link href="/img/favicon.png" rel="icon">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,600,700" rel="stylesheet">
  <style>
    .form-label {
        font-size: .95em;
        display: block;
        margin-bottom: .55em !important;
    }
    
    .form-control.form-control-lg {
        font-weight: 400;
        height: initial;
        padding: .5em .9em;
        margin-bottom: .9em;
        border: 1px solid rgba(0, 0, 0, 0.42);
        border-radius: 4px;
    }
    .btn {
      border-radius: 4px;
      font-size: 1em;
      font-weight: 500;
    }

    .btn-primary {
      color: #fff;
      background: #5397d4;
    }

    .navbar.bg-dark {
      background: #4b7094 !important;
      z-index: 199;
      position: relative;
    }

    .navbar.bg-dark .container {
      padding: 0 20px;
    }

    .card {
      border-radius: 8px;
    }

    .btn-primary.btn-circle i {
      color: #fff;
    }

    .btn-circle {
      text-align: center;
      line-height: calc(45px - 1em);
      vertical-align: middle;
      width: 45px;
      height: 45px;
      border-radius: 50%;
    }

    .table th,
    .table td {
      vertical-align: middle;
    }

    h1 {
      margin-bottom: 20px;
    }
  </style>
  @yield('styles')
</head>

<body>
  <main>
    <div id="app" data-app>
      <v-app>
        <v-main>
          @include('layouts.navbar')
          @if( session('message') )
          <div class="alert text-center alert-{{ session('message_type') ?: 'info' }}">
            {{ session('message') }}
          </div>
          @endif
          @yield('content')
        </v-main>
      </v-app>
    </div>
  </main>
  <script src="{{ mix('js/app.js') }}" defer></script>
  @yield('scripts')
</body>

</html>