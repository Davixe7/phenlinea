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
    .navbar,.navbar.bg-dark {
      background: #ff6a03 !important;
    }
    .navbar .container {
      padding: 5px 20px;
    }
  </style>
</head>
<body>
<main class="pb-4">
  <div id="app" data-app>
    <v-app>
      <v-main>
        <public-store-navbar :commerce="{{ json_encode($store) }}" class="mb-4"></public-store-navbar>
        <div class="container">
          @if( $store->status == 'active' )
            <div class="row">
              <div class="col-md-3">
                <public-store-profile :commerce="{{json_encode( $store )}}"/>
              </div>
              <div class="col-md-9">
                  @if( count($menu) )
                    <public-store-menu :menu="{{json_encode( $menu )}}" :commerce="{{json_encode( $store )}}"/>
                  @else
                    <div class="alert alert-info">
                        Este comercio no ha registrado productos aún
                    </div>
                  @endif
              </div>
            </div>
          @else
            <div class="d-flex justify-content-center align-items-center" style="height: calc(100vh - 70px);">
              <h1 style="color:gray;">
                <div class="text-center"><i class="material-icons" style="font-size: 3em;">storefront</i></div>
                <span>Comercio no disponible actualmente</span>
              </h1>
            </div>
          @endif
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
                        Crea tu tienda aquí
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