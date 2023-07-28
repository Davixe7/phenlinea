@extends('layouts.super')
@section('styles')
<style>
  .form-control {
    color: rgba(0,0,0,.7);
    border-radius: 0;
    border-top: none;
    border-left: none;
    border-right: none;
    padding-left: 0;
  }
  .page-title {
    font-size: 24px;
    margin: 1em 0;
    justify-content: center;
    align-items: center;
  }
</style>
@endsection
@section('content')
<div class="container">
  <div class="h1 page-title text-center">
    Configurar instancia de<br>
    {{ $labels[$instance_type] }}
  </div>
  <div class="row">
    <div class="col-lg-3 mx-auto d-flex flex-column align-items-center">
      <div class="text-center">
        {{ $instance_id }}
      </div>
      <img src="{{ $base64 }}" alt="">

      <a onclick="document.querySelector('#instanceForm').submit()" class="btn btn-primary">
        Asignar Instancia
      </a>
      <form
        id="instanceForm"
        method="POST"
        action="{{ route('admin.whatsapp_clients.update', ['whatsapp_client'=>$whatsapp_client->id]) }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="{{$instance_type}}" value="{{ $instance_id }}">
      </form>
    </div>
  </div>
</div>
@endsection