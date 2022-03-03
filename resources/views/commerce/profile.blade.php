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
      background: #ff6a03 !important;
    }
  </style>
</head>
<body>
<main class="pb-4">
  <div id="app" data-app>
    <v-app>
      <v-main>
        <commerce-navbar  :commerce="{{ json_encode($commerce) }}" class="mb-4"></commerce-navbar>
        <div class="container">
          <commerce-profile :commerce="{{ json_encode($commerce) }}"></commerce-profile>
        </div>
      </v-main>
    </v-app>
    <footer>
    <div classs="container">
        <div class="row">
            <div class="col-lg-6 ml-auto">
                <a href="#">
                    ¿Quieres una tienda como esta? | Contáctanos
                </a>
            </div>
        </div>
    </div>
</footer>
  </div>
</main>
<script src="{{ mix('js/app.js') }}" defer></script>
</body>
</html>
