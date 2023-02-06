@if( Auth::check() )
  <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
@else
  <nav class="navbar navbar-expand-md navbar-light shadow-sm">
@endif
  <div class="container p-0">
    <a class="navbar-brand" href="{{ url('/') }}">
      PHEnlinea
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mx-auto" style="align-items: center;">
        @if( Auth::guard('web')->check() )
        <li class="nav-item">
          <a class="nav-link" href="{{ route('admin.users.index') }}">SuperUsuarios</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('admin.admins.index') }}">Administradores</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('admin.porterias.index') }}">Porterias</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('admin.admins.export') }}">Exportar</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('admin.invoices.upload') }}">Facturas</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('admin.documentos.index') }}">Documentos Legales</a>
        </li>
        @endif
        @if( Auth::guard('admin')->check() )
          <li class="nav-item">
            <a class="nav-link" href="{{ route('extensions.index') }}">Extensiones</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('novelties.index') }}">Novedades</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('pqrs.index') }}">PQRS</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('visits.index') }}">Visitas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('invoices.index') }}" v-pre>
              Facturas <span>PSE</span> <img src="https://corbanca.com.co/wp-content/uploads/2022/06/pse.png" style="width: 60px;">
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('whatsapp.index') }}">
                <span class="mr-2">Mensajes Whatsapp</span>
                <img src="/img/icons8-whatsapp.svg" style="width: 20px; height: 20px;">
            </a>
          </li>
        @endif
        @if( Auth::guard('extension')->check() )
          <li class="nav-item">
            <a class="nav-link" href="{{ route('home') }}">Cartelera</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('docs.index') }}">Manuales & documentos</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('reminders.index') }}">Notificaciones</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('petitions.index') }}">Solicitudes</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('bills.index') }}">Enlaces</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/mis-facturas">Mis facturas</a>
          </li>
        @endif
      </ul>

      <!-- Right Side Of Navbar -->
      <ul class="navbar-nav">
          <li class="nav-item">
              @if( Auth::check() && Auth::user()->admin_id )
              <a class="nav-link" href="{{ 'https://api.whatsapp.com/send?phone=57' . Auth::user()->admin->phone . '&text=Hola,%20necesito%20asistencia%20relativa%20a%20PHEnLinea' }}">
                  Contáctanos
              </a>
              @else
              <a class="nav-link" href="https://api.whatsapp.com/send?phone=573144379170&text=Hola,%20necesito%20asistencia%20relativa%20a%20PHEnLinea">
                  Contáctanos
              </a>
              @endif
          </li>
      @if( Auth::check() )
        <li class="nav-item dropdown">
          <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" v-pre>
            <span>
                {{ explode("@", Auth::user()->email)[0] }}
            </span>
            @if( Auth::user()->admin_id )
            <span>
                # {{ Auth::user()->name  }}
            </span>
            @endif
            <span class="caret">
            </span>
          </a>
          <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="{{ route('logout') }}"
            onclick="event.preventDefault();
            document.getElementById('logout-form').submit();">
            Salir
          </a>
          @if(auth()->getDefaultDriver() == 'web')
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
          @else
            <form id="logout-form" action="{{ route(Auth::getDefaultDriver().'s.logout') }}" method="POST" style="display: none;">@csrf</form>
          @endif
        </div>
      </li>
      @else
        <li class="nav-item">
          <a class="nav-link" href="{{ route('admins.login') }}">Ingresar</a>
        </li>
      @endif
    </ul>
  </div>
</div>
</nav>
