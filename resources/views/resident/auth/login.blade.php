<!DOCTYPE html>
<html lang="es" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" href="/img/favicon.png">
  <title>PHenlinea</title>
  <script src="/js/app.js" defer></script>
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
      margin: 60px 0;
    }

    .feature {
      display: flex;
      align-items: center;
      text-transform: capitalize;
      font-weight: 500;
      color: #000;
    }

    .feature i {
      /* margin-right: 10px; */
    }
  </style>
</head>

<body>
  <div id="app">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-4 px-lg-0" style="z-index: 3000; position: relative;">
          <div id="login-form">
            <div class="form-title">
              <img src="/img/logo.png" alt="" style="width: 144px;">
              <div class="d-flex align-items-center justify-content-center">
                Propietarios & Residentes
              </div>
            </div>
            <form action="{{ route('residents.login') }}" method="POST">
              @csrf
              <div class="form-group">
                <label for="unit">
                  Seleccionar Unidad Residencial
                </label>
                <select id="unit" class="form-control" name="admin_id">
                    @foreach($admins as $admin)
                    <option value="{{ $admin->id }}">
                      {{ $admin->name }}
                    </option>
                    @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="extension">Número apartamento</label>
                <input type="text" class="form-control @error('extension') is-invalid @enderror" name="extension">
                @error('extension')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
              </div>
              <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="current-password" required>
                @error('password')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-secondary btn-login">
                  Acceder al sistema
                </button>
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