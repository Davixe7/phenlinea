<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'PHenlínea') }}</title>
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="{{ mix('css/app.css') }}" rel="stylesheet">
  <link href="/img/favicon.png" rel="icon">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,600,700" rel="stylesheet">
  <style>
    #app {
        height: 100vh;
        display: flex;
        flex-flow: row nowrap;
        justify-content: center;
        align-items: center;
    }
    .card {
        border-radius: 5px;
    }
  </style>
  @yield('styles')
</head>

<body>
  <main>
    <div id="app">
      <div class="col-lg-3">
          <div class="card">
          <div class="card-body">
              <p>
                  Se enviaran sus credenciales al número de teléfono asociado a su cuenta
              </p>
          </div>
          <div class="card-footer bg-white d-flex justify-content-end">
              <a href="{{ route('login') }}" class="btn btn-link mr-2">
                  Cancelar
              </a>
              <button class="btn btn-primary">
                  Enviar credenciales
              </button>
          </div>
      </div>
      </div>
    </div>
  </main>
  @yield('scripts')
</body>

</html>