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
    #app {
      background: url('https://phenlinea.com/img/building.jpg');
    }
  </style>
</head>
<body>
<main class="pb-4">
  <div id="app" data-app>
    <v-app>
      <v-main>
        <div class="container">
            <div class="row" style="height: 100vh; displax: flex;">
              <div class="col-md-5 mx-auto">
                <div class="text-center py-3">
                  <img src="{{ asset('img/logo.png') }}" alt="" style="width: 150px;">
                </div>
                <factura :admins="{{ json_encode( $admins ) }}"/>
              </div>
            </div>
        </div>
      </v-main>
    </v-app>
  </div>
</main>
<script src="{{ mix('js/app.js') }}" defer></script>
@yield('scripts')
</body>
</html>