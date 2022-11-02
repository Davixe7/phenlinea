@extends('layouts.legacy')

@section('styles')
<style>
  .list-group-item {
    border-left: 0;
    border-right: 0;
  }

  .list-group-item label {
    margin-bottom: 0;
  }

  .list-group-item:first-child {
    border-top: none;
  }
</style>
@endsection

@section('content')
@if( !isset($extensions) )
<div class="container">
  <div class="table-responsive">
    <h1>
      WhatsApp - Mensajería general
    </h1>
    <div class="text-secondary">
      {{ auth()->user()->whatsapp_instance_id }}
    </div>
    @if( isset( $base64 ) )
      <img src="{{ $base64 }}" alt="">
    @endif
  </div>
</div>
@else
<form action="{{ route('whatsapp.send') }}" method="POST">
  @csrf
  <div class="row">
    <div class="col-lg-3">
      <div class="card">
        <div class="card-header">
          Seleccionar destinatarios
          <div>
            <i>
              Seleccionados <span id="receivers-count">0</span>
            </i>
          </div>
        </div>
        <div class="card-body p-0" style="max-height: calc(100vh - 200px); overflow: auto;">
          <ul class="list-group p-0">
            <li class="list-group-item">
              <input type="checkbox" name="select_all" id="checkbox-select_all" class="mr-3">
              <label for="checkbox-select_all">Seleccionar todos</label>
            </li>
            <li class="list-group-item">
              <input type="checkbox" name="owners_only" id="checkbox-owners_only" value="true" class="mr-3">
              <label for="checkbox-owners_only">Solo propietarios</label>
            </li>
            @foreach($extensions as $extension)
            <li class="list-group-item">
              <input type="checkbox" name="receivers[]" id="checkbox-{{$extension->id}}" class="mr-3 extension-check @if($extension->owner_phone) hasOwnerPhone @endif" value="{{ $extension->id }}">
              <label for="checkbox-{{$extension->id}}">{{ $extension->name }}</label>
            </li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
    <div class="col-lg-6">
      <a href="{{ route('whatsapp.logout') }}">Cerrar sesión</a>
      <div class="table-responsive">
        <textarea id="message" placeholder="Escribe un mensaje" rows="10" name="message" class="form-control mb-3" required>Lorem ipsum dolor sit amet</textarea>
        <div class="d-flex justify-content-end">
          <button class="btn btn-primary">
            Enviar
          </button>
        </div>
      </div>
    </div>
  </div>
</form>
@endif
@endsection

@section('scripts')
<script>
  const extensionsChecks = document.querySelectorAll('.extension-check')
  const ownersOnlyCheckbox = document.querySelector('#checkbox-owners_only')
  const selectAll = document.querySelector('#checkbox-select_all')

  extensionsChecks.forEach(check=>{
    check.addEventListener('change', updateCount)
  })

  selectAll.addEventListener('change', function(e) {
    if (!e.target.checked) return
    extensionsChecks.forEach(check => {
      check.checked = true;
      check.setAttribute('readonly', true)
    })
    updateCount()
  })

  ownersOnlyCheckbox.addEventListener('change', function(e) {
    if (e.target.checked) {
      extensionsChecks.forEach(check => {
        check.checked = false
        check.style.display = 'none'
        check.parentElement.style.display = 'none'
        check.setAttribute('disabled', true)
      })
      document.querySelectorAll('.extension-check.hasOwnerPhone').forEach(check => {
        check.removeAttribute('disabled')
        check.checked = true
        check.style.display = 'inline-block'
        check.parentElement.style.display = 'inline-flex'
      })
    } else {
      extensionsChecks.forEach(check => {
        check.style.display = 'inline-flex'
        check.parentElement.style.display = 'inline-flex'
        check.removeAttribute('disabled')
      })
    }
    updateCount()
  })

  function updateCount(){
    let checked = Array.from(extensionsChecks).filter(check => {
      return (check.disabled == false) && (check.checked == true)
    })
    document.querySelector('#receivers-count').innerHTML = checked.length
  }
</script>
@endsection