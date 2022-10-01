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
        box-shadow: 7px 0px 10px 3px rgba(0,0,0,.065);
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
      
      label, .forgot-password {
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
      .feature i{
        /* margin-right: 10px; */
      }
        .whatsapp-card {
          display: inline-flex;
          border: none;
          background: none;
        }
        .whatsapp-card .card-header, .whatsapp-card .message, .whatsapp-card .whatsapp-input a {
          display: none;
        }
        .whatsapp-card .card-header, .whatsapp-card .whatsapp-input a {
          color: #fff;
          font-weight: 500;
        }
        .whatsapp-card .card-header {
          align-items: center;
          background: #4e8dc4;
        }
        .whatsapp-card .card-header span {
          cursor: pointer;
        }
        .whatsapp-card .card-body {
          padding: 0;
          background: none;
        }
        .whatsapp-card .message {
          padding: 10px;
          border-radius: 3px;
          background: #fff;
          margin-bottom: 20px;
          box-shadow: 0 1px 3px 1px rgba(0, 0, 0, 0.2);
        }
        .whatsapp-card .whatsapp-input {
          border-radius: 25px;
          width: fit-content;
          margin-left: auto;
        }
        .whatsapp-card .whatsapp-input .text {
          margin-right: 15px;
        }
        .whatsapp-card .whatsapp-input .logo-whatsapp {
          display: inline-block;
          width: 40px;
          filter: drop-shadow(0 1px 3px rgba(0, 0, 0, 0.25));
        }
        .whatsapp-card .whatsapp-input svg {
          display: none;
        }
        
        .whatsapp-card.active {
          position: absolute;
          right: 0;
          bottom: 0;
          z-index: 1000;
          width: 300px;
          background: #fff;
          box-shadow: 0 1px 13px 1px rgba(0, 0, 0, 0.25);
        }
        .whatsapp-card.active .card-header {
          display: flex;
        }
        .whatsapp-card.active .card-body {
          padding: 20px;
          background: #fff;
        }
        .whatsapp-card.active .message {
          display: inline-block;
        }
        .whatsapp-card.active .whatsapp-input {
          padding: 5px 15px 5px 20px;
          background: #4e8dc4;
        }
        .whatsapp-card.active .whatsapp-input a {
          display: inline-block;
        }
        .whatsapp-card.active .whatsapp-input svg {
          display: inline-block;
        }
        .whatsapp-card.active .whatsapp-input img {
          display: none;
        }
      .freelance-overlay {
          position: absolute;
          z-index: 100;
          background: rgba(0,0,0,.5);
          color: #fff;
          width: 100%;
          height: 100vh;
          display: flex;
          align-items: center;
          justify-content: center;
          text-align: center;
        }
        .freelance-overlay .caption {
            font-size: 35px;
        }
        .freelance-overlay .caption h4 {
            font-size: 42px;
        }
        .phones {
            font-size: 26px;
            font-weight: 500;
        }
      .btn-login {
          margin: 30px auto !important;
      }
    </style>
  </head>
  <body>
    <div id="app">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-4 px-lg-0" style="z-index: 3000; position: relative;">
            <div id="login-form">
              <form method="POST" action="{{ route('freelancers.login') }}">
                @csrf
                <div class="form-title">
                  <img src="/img/144x144.png" alt="">
                  <div>
                    Ingreso de Freelancers
                  </div>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
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
                <a href="#" class="forgot-password">¿Olvidaste la contraseña?</a>
                <button type="submit" class="btn btn-secondary btn-login">
                  Acceder al sistema
                </button>
                <div class="d-flex align-items-center py-4">
                    <div class="login-form-copy">
                  &copy; PHenlinea 2020 | Contáctenos
                </div>
                <!-- Whatsapp Card -->
                <div class="card whatsapp-card ml-auto">
                  <div class="card-header">
                    <span>Soporte via WhatsApp</span>
                    <div class="ml-auto">
                      <span onclick="document.querySelector('.whatsapp-card').classList.remove('active')">
                        <i class="material-icons" style="color: #fff;">close</i>
                      </span>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="message">¡Hola, somos PHEnlinea!<br> También brindamos asesoria personalizada gratuita!</div>
                    <div class="whatsapp-input text-right">
                      <img
                        src="/img/logo-whatsapp.svg"
                        class="logo-whatsapp"
                        onclick="document.querySelector('.whatsapp-card').classList.add('active')">
                      <a
                        href="https://api.whatsapp.com/send?phone=573144379170&text=Hola,%20estoy%20interesad@%20en%20PHEnLinea"
                        title="contacto whatsapp"
                        target="_blank">
                        <span class="text">Contactar a PHEnlinea.com</span>
                        <svg version="1.1" width="30px" height="25.714px" viewBox="625 347.143 30 25.714">
                           <polygon fill="#FFFFFF" points="625.015,347.143 625,357.143 646.429,360 625,362.857 625.015,372.857 655,360 "/>
                        </svg>
                      </a>
                    </div>
                  </div>
                </div><!-- Whatsapp Card -->
                </div>
              </form>
            </div>
          </div>
          <div class="d-none d-sm-block col-md-8 p-0" style="overflow-x: hidden; overflow-y: auto; max-height: 100vh; position: relative;">
            <div class="freelance-overlay">
              <div class="caption">
                <h4>¿Quieres trabajar con nosotros?</h4>
                Llámanos a los teléfonos<br>
                <span class="phones">3144379170 - 3006050430</span>
              </div>
            </div>
            <img src="/img/slide-freelancer-login.jpg" class="d-block w-100 h-100" alt="">
          </div>
        </div>
      </div>
    </div>
    <script src="{{ mix('js/app.js') }}" defer></script>
  </body>
</html>