<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>PHEnlinea</title>
  <link rel="icon" href="/img/favicon.png">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="/css/landing.css">
  <style>
    .custom-navbar-brand {
      margin-right: 40px;
    }

    .custom-sidenav {
      display: none;
      position: fixed;
      top: 120px;
      left: 0;
      right: 0;
      height: calc(100vh - 120px);
      background: #fff;
      z-index: 1000;
    }

    .custom-sidenav.active {
      display: block;
    }

    .custom-sidenav-nav {
      list-style-type: none;
      padding: 0;
      margin: 0;
    }

    .custom-sidenav-nav li {
      border-bottom: 1px solid rgba(0,0,0,.15);
      padding: 10px;
      position: relative;
    }

    .custom-sidenav-nav li.divider {
      font-size: 14px;
      font-weight: 600;
      margin-top: 20px;
      padding: 10px 20px;
    }
    .custom-sidenav-nav li.divider:first-child {
      margin-top: 0;
    }

    .brand-new {
      font-size: .9em;
      font-weight: 500;
      color: #fff;
      display: inline-block;
      border-radius: 3px;
      background: red;
      padding: 0 5px;
      position: absolute;
      top: -15px;
      right: 5px;
    }

    .login-bar {
      position: relative;
    }

    .carousel-item img {
      width: auto;
      max-width: 100%;
    }

    .carousel {
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 1px 20px 1px rgba(0, 0, 0, .15);
    }

    #productCarousel2 {
      margin: 0 auto !important;
    }

    @media(min-width: 991px) {
      .col-md-6:first-child {
        height: 80%;
        display: flex;
        flex-flow: column nowrap;
        justify-content: center;
      }

      .custom-sidenav,
      .sidenav-toggler {
        display: none !important;
      }
    }
  </style>
</head>

<body>
  <div id="app">
    <div class="custom-navbar">
      <a
        href="/"
        class="custom-navbar-brand">
        <img src="/img/logo.png" alt="logo phenlinea">
      </a>
      <ul class="custom-navbar-nav" style="margin-left: 0;">
        <li>
          <a
            href="{{ route('invoices.index') }}"
            class="btn btn-outline-primary d-none d-lg-flex">
            Consultar Factura
          </a>
        </li>
        <li>
          <a
            href="https://porterias.phenlinea.com"
            class="btn btn-outline-primary d-none d-lg-flex">
            Porterias
          </a>
        </li>
      </ul>

      <ul class="custom-navbar-nav ml-auto d-none d-lg-flex">
        <li>
          <a
            href="{{ route('login') }}"
            class="btn btn-outline-primary">
            Administrador
          </a>
        </li>
        <li>
          <a
            href="{{ route('residents.login') }}"
            class="btn btn-outline-primary">
            Propietario o Residente
          </a>
        </li>
      </ul>
      <button class="btn sidenav-toggler ml-auto" onclick="document.querySelector('.custom-sidenav').classList.toggle('active')">
        <i class="material-icons">menu</i>
      </button>
    </div>

    <div class="custom-sidenav d-sm-none">
      <ul class="custom-sidenav-nav">
        <li class="divider">
          Ingrese ahora
        </li>
        <li>
          <a href="{{ route('login') }}" class="btn btn-link">
            Ingresar Administrador
          </a>
        </li>
        <li>
          <a href="{{ route('residents.login') }}" class="btn btn-link">
            Ingresar Propietario o Residente
          </a>
        </li>
        <li class="divider">
          Otras Opciones
        </li>
        <li>
          <a
            href="{{ route('invoices.index') }}"
            class="btn btn-link">
            Consulte su Factura
          </a>
        </li>
        <li>
          <a
            href="https://porterias.phenlinea.com"
            class="btn btn-link">
            Control de Porteria
          </a>
        </li>
      </ul>
    </div>

    <div id="main">
      <div class="container-fluid">
        <!-- SLIDE 01 -->
        <div id="product-01" class="product row active">
          <div class="col-md-6">
            <div class="supheading">Citofonía</div>
            <h2>Conoce los beneficios de esta Citofonía GSM</h2>
          </div>
          <div class="col-md-6">
            <div id="productCarousel1" class="carousel slide" data-ride="carousel">
              <ol class="carousel-indicators">
                <li data-target="#productCarousel1" data-slide-to="0" class="active"></li>
                <li data-target="#productCarousel1" data-slide-to="1"></li>
                <li data-target="#productCarousel1" data-slide-to="2"></li>
                <li data-target="#productCarousel1" data-slide-to="3"></li>
                <li data-target="#productCarousel1" data-slide-to="4"></li>
                <li data-target="#productCarousel1" data-slide-to="5"></li>
                <li data-target="#productCarousel1" data-slide-to="6"></li>
                <li data-target="#productCarousel1" data-slide-to="7"></li>
              </ol>
              <div class="carousel-inner">
                <div class="carousel-item active">
                  <img src="/img/600/1.jpg" class="d-block mx-auto" alt="...">
                </div>
                <div class="carousel-item">
                  <img src="/img/600/2.jpg" class="d-block mx-auto" alt="...">
                </div>
                <div class="carousel-item">
                  <img src="/img/600/3.jpg" class="d-block mx-auto" alt="...">
                </div>
                <div class="carousel-item">
                  <img src="/img/600/4.jpg" class="d-block mx-auto" alt="...">
                </div>
                <div class="carousel-item">
                  <img src="/img/600/5.jpg" class="d-block mx-auto" alt="...">
                </div>
                <div class="carousel-item">
                  <img src="/img/600/6.jpg" class="d-block mx-auto" alt="...">
                </div>
                <div class="carousel-item">
                  <img src="/img/600/7.jpg" class="d-block mx-auto" alt="...">
                </div>
                <div class="carousel-item">
                  <img src="/img/600/8.jpg" class="d-block mx-auto" alt="...">
                </div>
              </div>

              <a class="carousel-control-prev" href="#productCarousel1" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
              </a>
              <a class="carousel-control-next" href="#productCarousel1" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
              </a>
            </div>
          </div>
        </div>

        <!-- SLIDE 02 -->
        <div id="product-02" class="product row">
          <div class="col-md-6">
            <div class="supheading">Proximamente App<br> y plataforma administrativa de</div>
            <div>
              <h2>Propietarios y Residentes</h2>
            </div>
          </div>
          <div class="col-md-6">
            <div id="productCarousel2" class="carousel slide" data-ride="carousel">
              <ol class="carousel-indicators">
                <li data-target="#productCarousel2" data-slide-to="0" class="active"></li>
                <li data-target="#productCarousel2" data-slide-to="1"></li>
                <li data-target="#productCarousel2" data-slide-to="2"></li>
                <li data-target="#productCarousel2" data-slide-to="3"></li>
                <li data-target="#productCarousel2" data-slide-to="4"></li>
                <li data-target="#productCarousel2" data-slide-to="5"></li>
                <li data-target="#productCarousel2" data-slide-to="6"></li>
                <li data-target="#productCarousel2" data-slide-to="7"></li>
                <li data-target="#productCarousel2" data-slide-to="8"></li>
              </ol>
              <div class="carousel-inner">
                <div class="carousel-item active">
                  <img src="/img/cartelera.jpg" class="d-block mx-auto" alt="...">
                </div>
                <div class="carousel-item">
                  <img src="/img/pqr.jpg" class="d-block mx-auto" alt="...">
                </div>
                <div class="carousel-item">
                  <img src="/img/manuales.jpg" class="d-block mx-auto" alt="...">
                </div>
                <div class="carousel-item">
                  <img src="/img/notificaciones.jpg" class="d-block mx-auto" alt="...">
                </div>
                <div class="carousel-item">
                  <img src="/img/encuestas.jpg" class="d-block mx-auto" alt="...">
                </div>
                <div class="carousel-item">
                  <img src="/img/clasificados.jpg" class="d-block mx-auto" alt="...">
                </div>
                <div class="carousel-item">
                  <img src="/img/domicilios.jpg" class="d-block mx-auto" alt="...">
                </div>
                <div class="carousel-item">
                  <img src="/img/links.jpg" class="d-block mx-auto" alt="...">
                </div>
              </div>

              <a class="carousel-control-prev" href="#productCarousel2" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
              </a>
              <a class="carousel-control-next" href="#productCarousel2" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div><!-- ENDS MAIN -->

    <footer>
      <div class="login-bar">
        <ul class="custom-navbar-nav">
          <li><a href="#" class="toggler active" data-target="#product-01">Citofonía</a></li>
          <li><a href="#" class="toggler" data-target="#product-02">Propietarios & Residentes</a></li>
        </ul>
        <div class="mobile-app-links">
          <a href="https://play.google.com/store/apps/details?id=com.phenlinea.inquilinos" target="_blank" class="d-inline-block mr-2">
            <img src="/img/googleplay.svg" style="width: 140px;" alt="">
          </a>
          <a href="#" class="d-inline-block text-left" style="color: #000;">
            <span>Proximamente...</span><br />
            <img src="/img/appstore.svg" style="width: 140px;" alt="">
          </a>
        </div>
        <div class="contact-bar">
          <div>
            <span>Contactanos</span>
            <div class="numbers">3053181323 | 3006050430</div>
          </div>

          <!-- Whatsapp Card -->
          <div class="card whatsapp-card">
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
                <img src="/img/logo-whatsapp.svg" class="logo-whatsapp" onclick="document.querySelector('.whatsapp-card').classList.add('active')">
                <a href="https://api.whatsapp.com/send?phone=573053181323&text=Hola,%20estoy%20interesad@%20en%20PHEnLinea" title="contacto whatsapp" target="_blank">
                  <span class="text">Contactar a PHEnlinea.com</span>
                  <svg version="1.1" width="30px" height="25.714px" viewBox="625 347.143 30 25.714">
                    <polygon fill="#FFFFFF" points="625.015,347.143 625,357.143 646.429,360 625,362.857 625.015,372.857 655,360 " />
                  </svg>
                </a>
              </div>
            </div>
          </div><!-- Whatsapp Card -->

        </div>
      </div>
    </footer>
  </div>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Righteous&display=swap">
  <script>
    $(document).ready(function() {
      const loginButton = $('a.btn-login')
      // Product togglers
      const togglers = $('.toggler')
      togglers.click(function() {
        let target = $(this).attr('data-target')
        $('.toggler.active').removeClass('active')
        $('.row.active').removeClass('active')
        $(this).addClass('active')
        $(target).addClass('active')
      })

      // Login options
      $('.login-options li label input').click(function(e) {
        loginButton.attr('href', $(this).val())
      })
    })
  </script>
</body>

</html>