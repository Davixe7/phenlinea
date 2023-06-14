@extends('layouts.app', ['title'=>'Registre su PQRS'])
@section('styles')
<style>
  .table-responsive h1 {
    padding: 30px;
  }

  .card-body {
    padding: 0 30px;
  }

  .form-group {
    margin-bottom: 35px;
  }

  .material-form .form-group label {
    display: block;
    font-size: 14px;
    margin-bottom: 10px;
  }

  .material-form .form-group .form-control {
    font-size: 16px;
    padding: 0;
    border: none;
    border-bottom: 1px solid #000;
    border-radius: 0;
    background: none;
  }

  .material-form .form-group .form-control:focus {
    border-bottom: 1px solid var(--primary);
    box-shadow: none;
  }

  .navbar.navbar-light {
    background: #4B7094;
    color: #fff;
  }

  .navbar.navbar-light a {
    color: #fff !important;
  }
</style>
@endsection
@section('content')
<div class="container">
  @if( session('message') )
  {{ session('message') }}
  @endif
  <div class="table-responsive mt-5">
    <h1>
      Peticiones, quejas o reclamos
    </h1>
    <div class="card-body material-form">
      <form action="{{ route('pqrs.store') }}" method="POST" enctype="multipart/form-data">
        <div class="form-group">
          <label>Unidad Residencial</label>
          <input class="form-control" type="text" value="{{ $admin->name }}" disabled>
          <input type="hidden" value="{{ $admin->id }}" name="admin_id">
        </div>
        <div class="form-group">
          <label>Nro apartamento</label>
          <input class="form-control" type="text" name="extension_name" required>
        </div>
        <div class="form-group">
          <label>Nombre y apellidos</label>
          <input class="form-control" type="text" name="name" required>
        </div>
        <div class="row">
          <div class="form-group col-lg-6">
            <label>Movil (A este móvil se envía el seguimiento del PQRS)</label>
            <input class="form-control" type="tel" name="phone" maxlength="10" required>
          </div>
        </div>
        <div class="form-group">
          <label>Descripción</label>
          <textarea class="form-control" name="description" required></textarea>
        </div>

        <label class="mb-3">Adjuntar imagen</label>
        <div class="form-group row">
          <div class="col-lg-4">
            <input type="file" name="media[]">
          </div>
          <div class="col-lg-4">
            <input type="file" name="media[]">
          </div>
          <div class="col-lg-4">
            <input type="file" name="media[]">
          </div>
        </div>
        <div class="d-flex justify-content-end">
          <button class="btn btn-primary text-white" type="submit">
            Enviar
          </button>
        </div>
        @csrf
      </form>
    </div>
  </div>
</div>
@endsection