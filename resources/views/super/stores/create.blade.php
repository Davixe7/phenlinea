@extends('layouts.app')
@section('content')
<div class="container">
  <h1 class="mb-3">Registrar comercio</h1>
  <form action="{{ route('admin.stores.store') }}" method="POST" enctype="multipart/form-data">
  <div class="row">
    <div class="col-lg-9">
      <div class="card">
        <div class="card-body">
            @csrf
            <div class="row">
                <div class="form-group col-lg-6">
                  <label class="form-label" for="name">Nombre del comercio</label>
                  <input type="text" class="form-control form-control-lg" name="name" required>
                </div>
                <div class="form-group col-lg-6">
                  <label class="form-label" for="nit">NIT</label>
                  <input type="text" class="form-control form-control-lg" name="nit" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-6">
                  <label class="form-label" for="phone">Teléfono</label>
                  <input type="tel" class="form-control form-control-lg" name="phone_1" required>
                </div>
                <div class="form-group col-lg-6">
                  <label class="form-label" for="email">Dirección de email</label>
                  <input type="email" class="form-control form-control-lg" name="email" required>
                </div>
            </div>
            <div class="form-group">
              <label class="form-label" for="address">Dirección</label>
              <input type="text" class="form-control form-control-lg" name="address" required>
            </div>
            <div class="form-group">
              <label class="form-label" for="description">Descripción</label>
              <textarea class="form-control form-control-lg" name="description"></textarea>
            </div>
        </div>
      </div>
    </div>
    
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                  <label class="form-label" for="category">Categoría</label>
                  <select class="form-control form-control-lg" name="category">
                    @foreach( ['Comidas', 'Ferreterías', 'Carnicerías', 'Supermercados', 'Licores', 'Legumbrerias', 'Tiendas', 'Otros'] as $category )
                        <option value="{{ $category }}">{{ $category }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">
                        Foto de perfil
                    </label>
                    <input class="form-control" type="file" name="logo">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary w-100 text-white justify-center">
                        Registrar comercio
                    </button>
                </div>
            </div>
        </div>
    </div>
    
  </div>
  </form>
  {{-- <create-store /> --}}
</div>
@endsection