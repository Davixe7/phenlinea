<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-v4-grid-only@1.0.0/dist/bootstrap-grid.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
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
      font-weight: 400;
      display: flex;
      height: 200px;
      flex-flow: column;
      justify-content: center;
      align-items: center;
      margin-bottom: 20px;
      padding: 20px;
      border-radius: 5px;
      border: 1px solid #1A61A3;
    }

    .menu-option:hover {
      background-color: #fff;
      box-shadow: 0 3px 10px #1A61A350;
    }

    .menu-option__icon {
      margin-bottom: 20px;
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
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }
    .btn-round i {
      font-size: 24px;
    }
  </style>

  <style>
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      font-size: 14px;
      text-align: left;
      height: 52px;
      border-bottom: 1px solid rgba(217, 217, 217, 1);
    }
    td {
      color: rgb(0,0,0,.87);
    }
    td:first-child, th:first-child {
      padding-left: 1em;
    }
    td:last-child, th:last-child {
      padding-right: 1em;
    }
    #extensions-table-wrap {
      border-radius: 5px;
      border: 1px solid rgba(217, 217, 217, 1);
    }
  </style>
@yield('styles')
</head>

<body>
  <div class="container-fluid" id="app">
    @include('layouts.navbar.new')
    <div class="main-content">
      @yield('content')
    </div>
  </div>

  <script src="{{ mix('js/app.js') }}" defer></script>
  @yield('scripts')
</body>
</html>
