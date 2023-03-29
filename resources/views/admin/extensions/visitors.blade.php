@extends('layouts.app')
@section('content')
<div class="container">
  <h1>
    Control Autorizados - Extension {{ $extension->name }}
  </h1>
  <div class="card mb-4">
    <div class="card-body">
      <table class="table table-striped">
        <thead>
          <th>
            Nombres y Apellidos
          </th>
          <th>
            Número de documento
          </th>
          <th>
            Placa
          </th>
          <th>
            Acciones
          </th>
        </thead>
        <tbody>
          @foreach($visitors as $visitor)
          <tr>
            <td style="text-transform: capitalize;">
              {{ $visitor->name }}
            </td>
            <td>
              {{ $visitor->dni }}
            </td>
            <td>
              {{ $visitor->plate ?: 'sin placa' }}
            </td>
            <td>
              <div class="btn-group">
                <button type="button" onclick="confirm('Seguro que desea eliminar el registro?') ? document.querySelector('#delete-visitor-{{ $visitor->id }}-form').submit() : ''">
                  <i class="material-symbols-outlined">delete</i>
                </button>
                <form method="POST" action="{{ route('visitors.delete', ['visitor'=>$visitor->id]) }}" id="delete-visitor-{{ $visitor->id }}-form">
                  @csrf
                  @method("DELETE")
                </form>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <h4 style="color: #000; font-size: 1.2rem;">
    Historial de visitas
  </h4>
  <div class="card">
    <div class="card-body">
      @if( $checkins && count( $checkins ) )
      <table class="table table-striped">
        <thead>
          <th>
            Nombres y Apellidos
          </th>
          <th>
            Número de documento
          </th>
          <th>
            Placa
          </th>
          <th>
            Fecha de visita
          </th>
        </thead>
        <tbody>
          @foreach($checkins as $checkin)
          <tr>
            <td>
              {{ $checkin->visitor->name }}
            </td>
            <td>
              {{ $checkin->visitor->dni }}
            </td>
            <td>
              {{ $checkin->visitor->plate ?: 'sin placa' }}
            </td>
            <td>
              {{ $checkin->created_at }}
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      @else
      <div class="alert alert-info">
        No hay visitas registradas para la extensión
      </div>
      @endif
    </div>
  </div>
  <div class="fab-container">
    <button type="button" class="btn btn-primary btn-circle" data-toggle="modal" data-target="#create-visitor-form">
      <i class="material-symbols-outlined">add</i>
    </button>
  </div>
  <div class="modal" tabindex="-1" id="create-visitor-form">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="POST" action="{{ route('visitors.store') }}">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title">Registrar Autorizado</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <input type="hidden" value="{{ $extension->id }}" name="extension_id">
            <div class="form-group">
              <label for="name">Nombres y Apellidos</label>
              <input type="text" class="form-control" name="name" required>
            </div>
            <div class="form-group">
              <label for="name">Número de documento</label>
              <input type="text" class="form-control" name="dni" required>
            </div>
            <div class="form-group">
              <label for="name">Fecha de autorización</label>
              <input type="date" class="form-control" name="authorized_at" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-link" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary">
              Enviar
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection