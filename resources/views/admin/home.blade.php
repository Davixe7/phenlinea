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
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/css/bootstrap-grid.min.css" rel="stylesheet"/>
  <link href="{{ mix('css/app.css') }}" rel="stylesheet">
  <title>Home</title>
  <style>
    body {
      background: #F3F6FC;
      font-family: 'Roboto', sans-serif;
    }

    .phenlinea-navbar {
      border-radius: 10px;
      background: #fff;
      padding: 10px 20px;
      margin: 10px auto 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
      position: relative;
    }

    .phenlinea-navbar__title {
      color: rgba(0, 0, 0, 87%);
      font-size: 19px;
      font-weight: 400;
      letter-spacing: .015em;
    }

    .phenlinea-navbar__brand {
      max-height: 50px;
      position: 50%;
      transform: translateX(-50%);
    }

    .phenlinea-navbar__actions {
      max-height: 50px;
    }

    .menu-option {
      position: relative;
      text-align: center;
      font-weight: 400;
      display: flex;
      height: 200px;
      flex-flow: column;
      justify-content: center;
      align-items: center;
      margin-bottom: 20px;
      border-radius: 5px;
    }

    .menu-option:hover {
      /* background-color: #fff;
      box-shadow: 0 3px 10px #1A61A350; */
    }

    .menu-option__icon {
      height: 200px;
    }

    .menu-option__icon img {
      margin-bottom: 20px;
      max-width: 205px;
    }

    .menu-option__icon i {
      color: #1A61A3;
      font-size: 54px;
    }

    .menu-option__title {
      color: #000;
      font-size: 19px;
      letter-spacing: .015em;
    }

    .btn-round {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .btn-round i {
      font-size: 24px;
    }

    .brandnew-tag {
      color: #fff;
      font-size: 13px;
      font-weight: 500;
      border-radius: 5px;
      background: red;
      padding: .25rem;
      position: absolute;
      top: 1em;
      right: 1.5em;
    }
  </style>
</head>

<body>
  <div class="phenlinea-navbar">
    <div class="phenlinea-navbar__title">
      Menú principal
    </div>
    <div class="phenlinea-navbar__brand">
      <img src="{{ asset('img/logo.png') }}" alt="" style="width: 120px; margin-top: -20px;">
    </div>
    <div class="phenlinea-navbar__actions">
      <div class="d-flex align-items-center">
        @auth
        <div class="me-3">
          {{ auth()->user()->name }}
        </div>
        @endauth
        <form action="{{ route('logout') }}" method="post" id="logoutForm">
          @csrf
        </form>
        <button type="button" class="btn btn-round" onclick="document.querySelector('#logoutForm').submit()">
          <i class="material-symbols-outlined">logout</i>
        </button>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="menu-options row">
      <div class="col-sm-6 col-lg-3">
        <a href="{{ route('extensions.index') }}">
          <div class="menu-option">
            <div class="menu-option__icon">
              <img src="{{ asset('img/iconos/citofonia.png') }}" alt="">
            </div>
          </div>
        </a>
      </div>

      <div class="col-sm-6 col-lg-3">
        <a href="{{ route('visits.index') }}">
          <div class="menu-option">
            <div class="menu-option__icon">
              <img src="{{ asset('img/iconos/visitas.png') }}" alt="">
            </div>
          </div>
        </a>
      </div>

      <div class="col-sm-6 col-lg-3">
        <a href="{{ route('novelties.index') }}">
          <div class="menu-option">
            <div class="menu-option__icon">
              <img src="{{ asset('img/iconos/novedades.png') }}" alt="">
            </div>
          </div>
        </a>
      </div>

      <div class="col-sm-6 col-lg-3">
        <a href="{{ route('invoices.index') }}">
          <div class="menu-option">
            <div class="menu-option__icon">
              <img src="{{ asset('img/iconos/facturas.png') }}" alt="">
            </div>
          </div>
        </a>
      </div>
    </div>

    <div>
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
  </div>
</body>

</html>