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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <title>PHenlínea</title>
  @yield('styles')
</head>

<body>
  @if( !auth()->check() || auth()->user()->device_community_id)
    <div class="alert alert-danger text-center">
      Esta unidad no pertenece a un comunidad zhyaf
    </div>
  @endif
  <div class="container-fluid" id="app">
    @include('layouts.navbar.new')
    <div class="main-content">
      @yield('content')
    </div>
  </div>

  <div class="btn btn-fab" style="position: fixed; right: 20px; bottom: 100px; border: 1px solid #000;">
    <a
      target="_blank"
      title="Soporte Whatsapp"
      href="https://api.whatsapp.com/send?phone=573144379170&text=Hola,%20necesito%20asistencia%20relativa%20a%20PHEnLinea">
      <img
        src="{{ asset('img/iconos/soporte.png') }}"
        alt="soporte"
        style="max-width: 100%;"
        />
    </a>
  </div>

  <script src="{{ mix('js/app.js') }}" defer></script>
  @yield('scripts')
  @stack('scripts')
</body>

</html>