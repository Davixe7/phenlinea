@extends('layouts.app')
@section('styles')
<style>
  .card {
    border-radius: 5px;
    background: #fff;
    box-shadow: 0px 1px 3px 1px rgba(0, 0, 0, .15);
  }

  .card  h1 {
    font-size: 1.3rem;
    margin: 0;
    padding: .75rem 1.15rem;
  }

  .list-group-item {
    border-left: none;
    border-right: none;
  }
  .btn-link {
    text-transform: uppercase;
    font-weight: 500;
    font-size: 1rem;
  }
</style>
@endsection
@section('content')
<div class="container">
  <div class="row">
    <div class="col-lg-6">
      <div class="card">
        <div class="py-3">
          <h1>
            Detalles de la solicitud
          </h1>
        </div>
        <ul class="list-group" style="padding-left: 0;">
          <li class="list-group-item d-flex">
            <div style="flex: 1;">
              <b>Apartamento</b>
              <div>{{ $petition->extension->name }}</div>
            </div>
            <div style="flex: 1;">
              <b>Creada el</b>
              <div>{{ $petition->created_at }}</div>
            </div>
          </li>
          <li class="list-group-item">
            <b>Teléfono</b>
            <div>{{ $petition->phone }}</div>
          </li>
          <li class="list-group-item">
            <b>Email</b>
            <div>{{ $petition->email }}</div>
          </li>
          <li class="list-group-item">
            <b>Detalles</b>
            <div>{{ $petition->description }}</div>
          </li>
        </ul>
        @if( !auth()->user()->admin_id )
        <div class="d-flex p-3 justify-content-end">
          <button class="btn btn-link">
            Ignorar
          </button>
          <button class="btn btn-link">
            Denegar
          </button>
          <button class="btn btn-link">
            Aprobar
          </button>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection