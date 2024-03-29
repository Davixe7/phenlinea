<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'PHenlinea') }}</title>
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="{{ asset('/img/favicon.png') }}" rel="icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,600,700" rel="stylesheet">
  <style>
    .table-responsive {
      max-height: calc(100vh - 100px);
    }
    .btn.btn-primary:hover {
      background: darkblue;
    }
    .dropdown-item {
      color: #000 !important;
    }
  </style>
  @stack('styles')
</head>

<body>
  <main>
    <div id="app" data-app>
      @include('layouts.navbar')
      @if( session('message') )
      <div class="alert text-center alert-{{ session('message_type') ?: 'info' }}">
        {{ session('message') }}
      </div>
      @endif
      @yield('content')
    </div>
  </main>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

  <script src="{{ mix('js/super.js') }}" defer></script>
  @yield('scripts')
</body>

</html>
