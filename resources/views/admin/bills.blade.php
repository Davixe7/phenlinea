@extends('layouts.app')
@section('content')
<div class="container">
  <h1>
    Enlaces de pago
  </h1>
  <div class="row">
    <div class="col-lg-5">
      <div class="card">
        <div class="card-header">
          <div class="form-section-title mb-0">
            Registre un nuevo enlace
          </div>
        </div>
        <div class="card-body">
          <form action="{{ route('bills.store') }}" method="POST">
            @csrf
            <div class="form-group">
              <label for="title">Concepto</label>
              <input type="text" class="form-control" name="title" placeholder="Concepto" required>
            </div>
            <div class="form-group">
              <label for="title">Enlace</label>
              <input type="url" class="form-control" name="url" placeholder="Ej: http://pasarela-de-pago.com" required>
            </div>

            <div class="text-right">
              <button type="button" @click="clearForm" class="btn btn-link">Cancelar</button>
              <button type="submit" v-show="!editing" class="btn btn-primary">Guardar</button>
              <button type="button" v-show="editing" @click="updateBill" class="btn btn-primary">Actualizar</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-lg-5 ml-lg-auto">
      <div class="form-section-title">
        Enlaces de pago
      </div>
      <ul class="list-group pl-0">
        @foreach( $bills as $bill )
        <li class="list-group-item d-flex">
          <div>
            <div>
              {{ $bill->title }}
            </div>
            <a href="{{ $bill->url }}">
              {{ $bill->url }}
            </a>
          </div>
          <div class="ml-auto">
            <button type="button" class="btn btn-link" data-toggle="dropdown">
              <i class="material-icons">more_vert</i>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
              <button
                type="button"
                class="dropdown-item"
                onclick="if( confirm('¿Seguro que desea eliminar el enlace?') ){ document.querySelector('#delete-post-{{ $bill->id }}-form').submit() }">
                Eliminar
              </button>
              <form
                action="{{ route('bills.destroy', ['bill'=>$bill->id]) }}"
                method="POST"
                id="delete-post-{{$bill->id}}-form">
                @csrf
                @method('DELETE')
              </form>
            </div>
          </div>
        </li>
        @endforeach
      </ul>
    </div>
  </div>
</div>
@endsection