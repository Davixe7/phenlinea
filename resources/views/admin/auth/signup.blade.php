<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Laravel') }}</title>
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="/img/favicon.png" rel="icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
  @yield('styles')
  <style>
    #app {
      padding: 20px;
    }

    img {
      margin-bottom: 15px;
    }

    .hero {
      font-size: 25px;
      font-weight: 600;
      text-align: center;
      margin-bottom: 20px;
    }

    @media(min-width: 768px) {
      #app {
        /* padding: 0; */
      }

      .hero {
        font-size: 50px;
      }
    }

    .form-section-title {
      display: block;
      font-size: 13px;
      color: #2D2D2D;
      text-transform: uppercase;
      margin-bottom: 20px;
    }

    .hero small {
      font-size: 18px;
      font-weight: 400;
      display: block;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      font-weight: 500;
      font-size: 13px;
      margin-bottom: 5px;
    }

    #app>.container-fluid>.row {
      display: flex;
      align-items: center;
      height: 100vh;
    }

    .first-column {
      display: flex;
      flex-flow: column nowrap;
      justify-content: center;
      align-items: center;
    }

    .first-column img {
      width: 150px;
    }

    .card {
      box-shadow: 0 1px 15px 1px rgba(0, 0, 0, .125);
      border-radius: 10px;
    }

    .card-body {
      padding: 30px;
    }

    .text-right {
      text-align: right;
    }
  </style>
</head>

<body>
  <main class="pb-4">
    <div id="app">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-5 first-column">
            <img src="{{ asset('img/logo.png') }}" alt="logo phenlinea">
            <div class="hero">
              Registrese Ahora,
              <small>
                Administre su unidad residencial como nunca antes
              </small>
            </div>
          </div>
          <div class="col-lg-6">
            <div id="user-registration-form">
              <div class="card">
                <form action="{{ route('register') }}" method="POST">
                  @csrf
                  <div class="card-body">
                    <div class="form-group">
                      <label for="name">Nombre de Unidad Residencial</label>
                      <input type="text" name="name" value="{{ old('name') }}" id="name" class="form-control @error('name') is-invalid @enderror" required>
                      @error('name')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>

                    <div class="row">
                      <div class="form-group col">
                        <label for="nit">NIT</label>
                        <input type="text" name="nit" value="{{ old('nit') }}" id="nit" class="form-control @error('nit') is-invalid @enderror" required>
                        @error('nit')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                      <div class="form-group col">
                        <label for="email">Email</label>
                        <input type="email" name="contact_email" value="{{ old('contact_email') }}" id="contact_email" class="form-control @error('contact_email') is-invalid @enderror" required>
                        @error('contact_email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="address">Dirección</label>
                      <input type="text" name="address" value="{{ old('address') }}" id="address" class="form-control @error('address') is-invalid @enderror" required>
                      @error('address')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>

                    <div class="form-section-title">
                      Información del Administrador
                    </div>

                    <div class="row">
                      <div class="form-group col">
                        <label for="name">Nombre</label>
                        <input type="text" name="name" value="{{ old('name') }}" id="name" class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>

                      <div class="form-group col">
                        <label for="phone">Celular</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" id="phone" class="form-control @error('phone') is-invalid @enderror" required>
                        @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>

                    <div class="form-section-title">
                      Datos de Acceso
                    </div>

                    <div class="form-group col">
                      <label for="email">Usuario</label>
                      <input type="email" name="email" value="{{ old('email') }}" id="email" class="form-control @error('email') is-invalid @enderror" required>
                      @error('email')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>

                    <div class="row">
                      <div class="form-group col">
                        <label for="password">Contraseña</label>
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                      <div class="form-group col">
                        <label for="password_confirmation">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" required>
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>

                    <div class="text-right">
                      <button type="submit" class="btn btn-primary">
                        Registrar Unidad
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,600,700" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
  @yield('scripts')
</body>

</html>