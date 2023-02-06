<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'PHenlinea') }}</title>
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="/img/favicon.png" rel="icon">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,600,700" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ mix('css/app.css') }}" rel="stylesheet">
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

    .table-responsive {
      border-radius: 5px;
      background: #fff;
      padding-bottom: 2.75rem;
      box-shadow: 0px 1px 3px 1px rgba(0, 0, 0, .15);
    }

    .navbar-nav .nav-item a.nav-link {
      padding-left: 1rem;
      padding-right: 1rem;
      border-right: 1px solid #ffffff30 !important;
      font-size: 1em !important;
    }

    .table-responsive h1 {
      font-size: 1.3rem;
      margin: 0;
      padding: .75rem 1.15rem;
    }

    .table tr:last-child td {
      border-bottom: 1px solid #dee2e6;
    }

    .table th {
      font-size: .85rem !important;
    }

    .table th,
    .table td {
      padding: .75rem 1.15rem;
    }

    .table th:first-child {
      border-left: none !important;
    }

    .table th:last-child {
      border-right: none !important;
    }

    .table td {
      font-size: .85rem !important;
      font-weight: 500;
      color: rgba(0, 0, 0, .75);
    }
  </style>
  @yield('styles')
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
  <script src="{{ mix('js/app.js') }}" defer></script>
  @yield('scripts')
</body>
</html>
