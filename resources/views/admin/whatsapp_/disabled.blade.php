<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/css/bootstrap.min.css" rel="stylesheet"/>
  <title>Comunidad QR</title>
  <style>
    .main-container {
      height: 100vh;
      background: #5297D4;
      font-family: 'Roboto', sans-serif;
    }
  </style>
</head>
<body>
<div class="main-container d-flex align-items-center justify-content-center text-center">
  <div>
    <img src="{{ asset('img/iconos/logo_comunidad.png') }}" alt="" style="width: 250px; margin-bottom: 30px;">
    <h4 style="font-size: 19px; color: #fff;">
      Módulo deshabilitado
    </h4>
    <p style="font-size: 16px; color: #fff;">
      Comuniquese con la administración del sistema para habilitar este módulo
    </p>
    <a class="btn btn-outlined btn-outlined-secondary" href="{{ route('home') }}" style="border: 1px solid #fff; color: #fff;">
      Volver al inicio
    </a>
  </div>
</div>
</body>
</html>