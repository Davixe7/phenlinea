@extends('layouts.app')
@section('content')
<div class="container">
    <div class="mb-4 d-flex justify-space-between align-items-center">
        <h1 style="margin: 0;">
          Actualizar comercio
        </h1>
        <span style="font-size: .8em; color: rgba(0,0,0,.5);">
          {{ $store->_password }}
        </span>
    </div>
  <form action="{{ route('admin.stores.update', ['store'=>$store->id]) }}" method="POST" enctype="multipart/form-data">
  <div class="row">
    <div class="col-lg-9">
      <div class="card">
        <div class="card-body">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="form-group col-lg-6">
                  <label class="form-label" for="name">Nombre del comercio</label>
                  <input type="text" class="form-control form-control-lg" name="name" value="{{ $store->name }}" required>
                </div>
                <div class="form-group col-lg-6">
                  <label class="form-label" for="nit">NIT</label>
                  <input type="text" class="form-control form-control-lg" name="nit" value="{{ $store->nit }}" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-6">
                  <label class="form-label" for="phone">Teléfono</label>
                  <input type="tel" class="form-control form-control-lg" name="phone_1" value="{{ $store->phone_1 }}" required>
                </div>
                <div class="form-group col-lg-6">
                  <label class="form-label" for="email">Dirección de email</label>
                  <input type="email" class="form-control form-control-lg" name="email" value="{{ $store->email }}" required>
                </div>
            </div>
            <div class="form-group">
              <label class="form-label" for="address">Dirección</label>
              <input type="text" class="form-control form-control-lg" name="address" value="{{ $store->address }}" required>
            </div>
            <div class="form-group">
              <label class="form-label" for="description">Descripción</label>
              <textarea class="form-control form-control-lg" name="description">{{ $store->description }}</textarea>
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
                        <option value="{{ $category }}" {{ $category == $store->category ? 'selected' : '' }} >{{ $category }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">
                        Foto de perfil
                    </label>
                    @if( $store->logo )
                        <img style="width: 100%;" src="/{{ $store->logo['url'] }}" class="mb-2">
                    @endif
                    <input class="form-control" type="file" name="logo">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary w-100 text-white justify-center">
                        Actualizar información
                    </button>
                </div>
            </div>
        </div>
    </div>
    
  </div>
  </form>
</div>
@endsection