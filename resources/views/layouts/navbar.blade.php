@if( Auth::check() )
  <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
@else
  <nav class="navbar navbar-expand-md navbar-light shadow-sm">
@endif
  <div class="container">
    <a class="navbar-brand" href="{{ url('/') }}">
      PHEnlinea
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
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
          <a class="nav-link" href="{{ route('admin.freelancers.index') }}">Freelancers</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('admin.admins.export') }}">Exportar</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('admin.stores.index') }}">Comercios</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('admin.invoices.upload') }}">Facturas</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('admin.documentos.index') }}">Documentos Legales</a>
        </li>
        @endif
        @if( Auth::guard('freelancer')->check() )
          <li class="nav-item">
            <a class="nav-link" href="/">Referidos</a>
          </li>
        @endif
        @if( Auth::guard('admin')->check() )
          <li class="nav-item">
            <a class="nav-link" href="{{ route('census.index') }}">Extensiones</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('posts.index') }}">Cartelera</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('docs.index') }}">Manuales & Doc.</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('reminders.index') }}">MSJ Apt</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('bills.index') }}">Enlaces</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('petitions.index') }}">Solicitudes</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('novelties.index') }}">Novedades</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('visits.index') }}">Visitas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/push">MSJ App</a>
          </li>
          <li class="nav-item dropdown">
            <a id="smsDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" v-pre>
              SMS <span class="caret"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item" href="{{ route('sms.index') }}">Enviar SMS</a>
              <a class="dropdown-item" href="{{ route('sms.log') }}">Historico</a>
            </div>
          </li>

          <li class="nav-item dropdown">
            <a id="facturasDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" v-pre>
              Facturas <span class="caret"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item" href="{{ route('invoices.index') }}">Mis facturas</a>
              <a class="dropdown-item" href="/facturas/upload">Facturación residentes</a>
            </div>
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
            <a class="nav-link" href="{{ route('petitions.create') }}">Solicitudes</a>
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
      <ul class="navbar-nav ml-auto">
          <li class="nav-item">
              @if( Auth::check() && Auth::user()->admin_id )
              <a class="nav-link" href="{{ 'https://api.whatsapp.com/send?phone=57' . Auth::user()->admin->phone . '&text=Hola,%20necesito%20asistencia%20relativa%20a%20PHEnLinea' }}">
                  <img src="/img/icons8-whatsapp.svg" style="width: 20px; height: 20px;"> Contáctanos
              </a>
              @else
              <a class="nav-link" href="https://api.whatsapp.com/send?phone=573144379170&text=Hola,%20necesito%20asistencia%20relativa%20a%20PHEnLinea">
                  <img src="/img/icons8-whatsapp.svg" style="width: 20px; height: 20px;"> Contáctanos
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
          <a class="nav-link" href="{{ route('admins.getLogin') }}">Ingresar</a>
        </li>
      @endif
    </ul>
  </div>
</div>
</nav>
