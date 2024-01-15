<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="{{ asset('favicon.png') }}" rel="icon">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <title>Home</title>
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      background: linear-gradient(to bottom, var(--bs-primary), var(--bs-primary) 25%, #F3F6FC 25%);
    }
    #app {
      min-height: 100vh;
      display: flex;
      flex-flow: column;
    }
    .footer {
      margin-top: auto;
      padding: 1rem 0;
      font-size: 13px;
      color: rgba(0,0,0,.5);
      border-top: 1px solid rgba(0,0,0,.15);
    }
  </style>
  @yield('styles')
  @stack('styles')
</head>

<body>
  <div class="container-fluid" id="app">
    <div class="public-navbar d-flex justify-content-center p-3">
      <img src="{{ asset('img/logo.png') }}" alt="" style="width: 100px;">
    </div>
    <div class="main-content">
      @yield('content')
    </div>

    <div class="footer">
      <div class="container">
        <div class="row">
          <div class="col-lg-4 text-center mx-auto">
            PHenlinea SAS &copy; 2024
          </div>
        </div>
      </div>
    </div>
  </div>

  <div
    class="btn btn-fab"
    style="position: fixed; right: 20px; bottom: 30px; background: #0d6efd;">
    <a
      class="text-white d-inline-flex"
      target="_blank"
      title="Soporte Whatsapp"
      style="text-decoration: none;"
      href="https://api.whatsapp.com/send?phone=573144379170&text=Hola,%20necesito%20asistencia%20relativa%20a%20PHEnLinea">
      <i
        class="material-symbols-outlined"
        style="font-size: 36px; text-decoration: none;">
        contact_support
      </i>
      <!-- <img
        src="{{ asset('img/iconos/soporte.png') }}"
        alt="soporte"
        style="max-width: 100%;"
        /> -->
    </a>
  </div>

  @yield('scripts')
  @stack('scripts')
</body>

</html>