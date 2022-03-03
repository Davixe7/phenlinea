<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Laravel') }}</title>
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link href="/img/favicon.png" rel="icon">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,600,700" rel="stylesheet">
  <style>
      .navbar.bg-dark {
          background: #4b7094 !important;
      }
  </style>
  @yield('styles')
</head>

<body>
  <main>
    <div id="app" data-app>
        @include('layouts.navbar')
        <div class="container">
            @if( session('message') )
              <div class="alert text-center alert-{{ session('message_type') ?: 'info' }}">
                {{ session('message') }}
              </div>
            @endif
            @yield('content')
        </div>
    </div>
  </main>
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
  @yield('scripts')
</body>

</html>