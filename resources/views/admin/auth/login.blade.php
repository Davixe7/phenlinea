<!DOCTYPE html>
<html lang="es" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" href="/img/favicon.png">
  <title>PHenlinea</title>
  <link rel="stylesheet" href="/css/app.css">
  <style>
    #app {
      font-family: "Roboto", sans-serif;
      font-size: 14px;
    }

    #login-form {
      background: #fff;
      display: flex;
      flex-flow: column;
      height: 100vh;
      padding: 15px 30px;
      box-shadow: 7px 0px 10px 3px rgba(0, 0, 0, .065);
    }

    .form-title {
      font-size: 1.25em;
      font-weight: 600;
      color: #838383;
      text-align: center;
      margin-bottom: 30px;
    }

    .form-title img {
      margin-bottom: 15px;
    }

    .login-form-copy {
      color: #838383;
      margin-top: auto;
    }

    label,
    .forgot-password {
      font-weight: 600;
      color: #838383;
      display: block;
    }

    .form-group {
      margin-bottom: 30px;
    }

    .form-control {
      font-size: 1.1428em;
      font-weight: 600;
      color: #484848;
      padding: 0 0 10px 0;
      border: none;
      border-bottom: 1px solid #3f3f3f;
      border-radius: 0;
    }

    .form-control:focus {
      box-shadow: none;
    }

    .btn-login {
      font-size: 1.25em;
      font-weight: 600;
      color: #fff;
      border: none;
      background: #000;
      border-radius: 2px;
      padding: 7px 24px;
      width: 100%;
      margin: 60px 0 20px 0;
    }

    .feature {
      display: flex;
      align-items: center;
      text-transform: capitalize;
      font-weight: 500;
      color: #000;
    }
  </style>
</head>

<body>
  <div id="app">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-4 px-lg-0" style="z-index: 3000; position: relative;">
          <div id="login-form">
            <form method="POST" action="{{ route('login') }}">
              @csrf
              <div class="form-title">
                <img src="/img/144x144.png" alt="">
                <div>
                  Panel de Administración
                </div>
              </div>
              <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input id="email" type="email" class="form-control @if( $errors->has('email') ) is-invalid @endif" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
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
              
              <a href="{{ route('password.request') }}" class="forgot-password">
                ¿Olvidaste la contraseña?
              </a>
              <button type="submit" class="btn btn-secondary btn-login">
                Acceder al sistema
              </button>
              <div class="login-form-copy">
                &copy; PHenlinea {{ Carbon\Carbon::now()->format('Y') }}
              </div>
            </form>
          </div>
        </div>
        <div class="d-none d-sm-block col-md-8 p-0" style="overflow-x: hidden; overflow-y: auto; max-height: 100vh;">
          <img src="/img/slide-admins-2.jpg" alt="" style="width: 100%;">
        </div>
      </div>
    </div>
  </div>
</body>

</html>