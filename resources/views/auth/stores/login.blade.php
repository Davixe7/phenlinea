<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PHEnlinea</title>
    <link rel="icon" href="/img/favicon.png">
    <link rel="stylesheet" href="/css/app.css">
    <link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">
    <style>
      h1 {
        font-weight: 600;
        font-size: 1.65em;
      }
    </style>
  </head>
  <body>
    <div id="app">
      <div class="container">
        <div class="row">
          <div class="col-lg-4 mx-auto">
            <img src="/img/logo.png" alt="logo PHEnlinea" style="width: 144px; margin-bottom: 15px;">
            <div id="login-form">
              <form method="POST" action="{{ route('stores.login') }}">
                @csrf
                <h1 class="form-title">Comercios</h1>
                <div class="form-group">
                    <label for="email">Email</label>
                  <input id="email" type="email" class="form-control @if( $errors->has('email') ) is-invalid @endif" name="_email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                  @if( $errors->has('email') )
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                  </span>
                  @endif
                </div>
    
                <div class="form-group">
                  <label for="password">Contraseña</label>
                  <input id="password" type="password" class="form-control @if( $errors->has('password') ) is-invalid @endif" name="password" required autocomplete="current-password">
                  @if( $errors->has('password') )
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('password') }}</strong>
                  </span>
                  @endif
                </div>
                <div class="d-flex align-items-center">
                  <a href="#" class="forgot-password">¿Olvidaste la contraseña?</a>
                  <button type="submit" class="btn btn-primary text-white ml-auto">
                    Acceder al sistema
                  </button>
                </div>
                <div class="d-flex align-items-center py-4">
                    <div class="login-form-copy">
                      &copy; PHenlinea 2020 | Contáctenos
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>