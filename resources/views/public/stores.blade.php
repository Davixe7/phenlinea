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
</head>
<body>
<main class="pb-4">
  <div id="app" data-app>
    <v-app>
      <v-main>
        <public-store-navbar :commerce="null" class="mb-4"></public-store-navbar>
        <div class="container">
          <public-stores :stores="{{ json_encode( $stores ) }}"/>
        </div>
      </v-main>
    </v-app>
  </div>
</main>
<footer>
<div class="container">
        <div class="row" style="padding: 1em 0; border-top: 1px solid #9f9f9f;">
            <div class="col-lg-6 ml-auto text-right">
                <a
                    target="_blank"
                    href="https://api.whatsapp.com/send?phone=573144379170&amp;text=Hola%2C%20me%20interesa%20crear%20mi%20tienda%20online" class="btn btn-success">
                <img src="/img/icons8-whatsapp.svg" style="width: 20px; height: 20px; margin-right: 10px;" style="color: gray; text-decoration: none;">
                    <span>
                        Crea tu tienda grátis
                    </span>
                </a>
            </div>
        </div>
    </div>
    </footer>
<script src="{{ mix('js/app.js') }}" defer></script>
@yield('scripts')
</body>
</html>