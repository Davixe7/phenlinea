<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/css/bootstrap-grid.min.css" rel="stylesheet" />
  <link href="{{ mix('css/app.css') }}" rel="stylesheet">
  <title>Home</title>
  <style>
    body {
      background: #F3F6FC;
      font-family: 'Roboto', sans-serif;
    }
  </style>
</head>

<body>
  @if(!auth()->check() || !auth()->user()->device_community_id)
    <div class="alert alert-danger text-center">
      Esta unidad no pertenece a un comunidad zhyaf
    </div>
  @endif
  @include('layouts.navbar.new', ['title'=>'Menú principal'])

  <div class="container">
    <div class="menu-options row">
      @php
      $routes = [
      [
      'name' => 'extensions.index',
      'icon' => 'citofonia.png'
      ],
      [
      'name' => 'visits.index',
      'icon' => 'visitas.png'
      ],
      [
      'name' => 'novelties.index',
      'icon' => 'novedades.png'
      ],
      [
      'name' => 'invoices.index',
      'icon' => 'facturas.png'
      ],
      ]
      @endphp

      @foreach($routes as $route)
      <div class="col-sm-6 col-lg-3">
        <a href="{{ route($route['name']) }}">
          <div class="menu-option">
            <div class="menu-option__icon">
              <img src="{{ asset('img/iconos/' . $route['icon']) }}" alt="">
            </div>
          </div>
        </a>
      </div>
      @endforeach
    </div>

    <div style="text-align: center; position: relative;">
      <hr style="position: absolute; top: 50%; left: 0; right: 0; border-color: #6c96d1;" />
      <img src="{{ asset('img/iconos/logo_comunidad.png') }}" style="display: inline-block; width: 200px; z-index: 100; position: relative;" />
    </div>

    <div class="row justify-content-center">
      <div class="col-sm-6 col-lg-3">
        <a href="{{ route('petitions.index') }}">
          <div class="menu-option">
            <div class="brandnew-tag">
              Nuevo
            </div>
            <div class="menu-option__icon">
              <img src="{{ asset('img/iconos/pqrs.png') }}" alt="">
            </div>
          </div>
        </a>
      </div>

      <div class="col-sm-6 col-lg-3">
        <a href="{{ route('whatsapp.index') }}">
          <div class="menu-option">
            <div class="brandnew-tag">
              Nuevo
            </div>
            <div class="menu-option__icon">
              <img src="{{ asset('img/iconos/whatsapp.png') }}" alt="">
            </div>
          </div>
        </a>
      </div>

      <div class="col-sm-6 col-lg-3">
        <a href="{{ route('whatsapp.comunity') }}">
          <div class="menu-option">
            <div class="brandnew-tag">
              Nuevo
            </div>
            <div class="menu-option__icon">
              <img src="{{ asset('img/iconos/comunidad.png') }}" alt="">
            </div>
          </div>
        </a>
      </div>
    </div>
  </div>
</body>

</html>