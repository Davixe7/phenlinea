@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-4 mx-auto">
      <div class="card">
        <div class="card-header">Ingresa con tus datos</div>

        <div class="card-body">
          <form method="POST" action="{{ route('admins.login') }}">
            @csrf

            <div class="form-group">
              <label for="email">Email</label>
              <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="davidguilarte7@gmail.com" required autocomplete="email" autofocus>
              @error('email')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>

            <div class="form-group">
              <label for="password">Contraseña</label>
              <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" value="123456">
              @error('password')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>

            <div class="form-group row mb-0">
              <div class="col">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                  <label class="form-check-label" for="remember">Recordarme</label>
                </div>
              </div>
              <div class="form-group col text-right mb-0">
                <button type="submit" class="btn btn-primary">Ingresar</button>
              </div>
            </div>

            <div class="form-group mb-0 text-center">
              <hr>
              @if (Route::has('password.request'))
              <a class="btn btn-link" href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
              @endif
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
