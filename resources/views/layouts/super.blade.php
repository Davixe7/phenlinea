<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'PHenlinea') }}</title>
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="{{ mix('css/app.css') }}" rel="stylesheet">
  <link href="/img/favicon.png" rel="icon">  
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,600,700" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js"></script>
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
  <script src="{{ mix('js/super.js') }}" defer></script>
  @yield('scripts')
</body>

</html>
