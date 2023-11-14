<div class="ph-navbar-wrap" style="background: #345493;">
  <nav class="ph-navbar">
    <div class="ph-navbar-brand" href="{{ url('/') }}">
      PHEnlinea
    </div>

    <ul class="ph-navbar-nav">
      @if( Auth::guard('web')->check() )
      @include('layouts.navbar.super')
      @endif
      @if( Auth::guard('admin')->check() )
      @include('layouts.navbar.admin')
      @endif
      @if( Auth::guard('extension')->check() )
      @include('layouts.navbar.resident')
      @endif
    </ul>

    <ul class="ph-navbar-nav">
      <li>
        @if( Auth::check() && Auth::user()->admin_id )
        <a href="{{ 'https://api.whatsapp.com/send?phone=57' . Auth::user()->admin->phone . '&text=Hola,%20necesito%20asistencia%20relativa%20a%20PHEnLinea' }}">
          Contáctanos
        </a>
        @else
        <a href="https://api.whatsapp.com/send?phone=573144379170&text=Hola,%20necesito%20asistencia%20relativa%20a%20PHEnLinea">
          Contáctanos
        </a>
        @endif
      </li>
      @if( Auth::check() )
      <li>
       <span class="text-white d-none d-sm-inline-block pe-3">
           {{ explode("@", Auth::user()->email)[0] }}
       </span>
        <a href="{{ route('logout') }}">
          <span>
            Salir
          </span>
        </a>
      </li>
      @else
      <li>
        <a href="{{ route('admins.login') }}">Ingresar</a>
      </li>
      @endif
    </ul>
  </nav>

  <div class="ph-navbar-toggler" onclick="document.querySelector('#app').classList.toggle('mobile-navbar-active')">
  <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M3.05 18.075v-2.15h17.925v2.15Zm0-5V10.9h17.925v2.175Zm0-5v-2.15h17.925v2.15Z"/></svg>
  </div>
</div>