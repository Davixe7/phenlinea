<div class="phenlinea-navbar">
  <div class="phenlinea-navbar__title">
    <a class="btn btn-round me-3" href="{{ route('home') }}">
      <i class="material-symbols-outlined">
        arrow_back
      </i>
    </a>
    {{ $title }}
  </div>
  <div class="phenlinea-navbar__brand">
    <img src="{{ asset('img/logo.png') }}" alt="" style="width: 120px; margin-top: -20px;">
  </div>
  <div class="phenlinea-navbar__actions">
    <form action="{{ route('logout') }}" method="post" id="logoutForm">
      @csrf
    </form>
    <a href="#" class="btn btn-round">
      <i class="material-symbols-outlined">logout</i>
    </a>
  </div>
</div>