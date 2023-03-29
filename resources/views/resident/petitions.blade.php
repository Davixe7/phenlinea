@extends('layouts.app')
@section('styles')
<style>
  .btn-link {
    font-weight: 500 !important;
    text-transform: uppercase;
  }
</style>
@endsection
@section('content')
<div class="container">
  <div class="row">
    <div class="col-lg-5">
      <div class="card">
        <div class="p-3 pb-0">
          <h1 style="font-size: 1.35rem;">
            Registrar Solicitud
          </h1>
        </div>
        <div class="p-3">
          <form action="{{ route('petitions.store') }}" method="POST">
            @csrf
            <div class="mb-3">
              <label for="title">Título</label>
              <input type="text" class="form-control" name="title" required>
            </div>
            <div class="mb-3">
              <label for="description">Descripción</label>
              <textarea rows="4" class="form-control" name="description" required></textarea>
            </div>
            <div class="mb-3">
              <label for="email">Email</label>
              <input type="email" class="form-control" name="email" required>
            </div>
            <div class="mb-3">
              <label for="phone">Teléfono</label>
              <input type="phone" class="form-control" name="phone" required>
            </div>
            <div class="pt-3 d-flex justify-content-end">
              <button type="submit" class="btn btn-link">
                Registrar
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-lg-7">
      <div class="table-responsive">
        <h1>
          Solicitudes
        </h1>
        <table class="table">
          <thead>
            <th>
              Fecha
            </th>
            <th>
              Título
            </th>
            <th>
              Estado
            </th>
            <th>
              Acciones
            </th>
          </thead>
          <tbody>
            @foreach($petitions as $petition)
            <tr>
              <td>
                {{ $petition->created_at }}
              </td>
              <td>
                {{ $petition->title }}
              </td>
              <td>
                {{ $petition->status }}
              </td>
              <td>
                <div class="btn-group">
                  <button class="btn btn-sm btn-link" onclick="window.confirm('Seguro que desea eliminar la solicitud?') ? document.querySelector('#petition-destroy-form-{{$petition->id}}').submit() : ''">
                    <i class="material-symbols-outlined" title="eliminar">delete</i>
                  </button>
                  <a href="{{ route('petitions.show', ['petition'=>$petition->id]) }}" class="btn btn-sm btn-link">
                    <i class="material-symbols-outlined" title="detalles">visibility</i>
                  </a>
                </div>
                <form action="{{ route('petitions.destroy', ['petition'=>$petition->id]) }}" id="petition-destroy-form-{{$petition->id}}" method="post">
                  @csrf
                  @method('DELETE')
                </form>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection