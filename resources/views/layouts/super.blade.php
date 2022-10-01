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
  <script src="{{ mix('js/super.js') }}" defer></script>
  @yield('scripts')
</body>

</html>