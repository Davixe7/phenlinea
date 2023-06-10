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
</style>
@endsection
@section('content')
<div class="container">
  <div class="h1 page-title" style="font-size: 24px; margin: 1em 0;">
    Configurar Clientes de WhatsApp
  </div>
  <div class="row">
    @foreach($whatsapp_clients as $client)
    <div class="col-lg-4">
      <div class="card">
        <div class="card-header" style="border-bottom: none;">
          <div style="font-weight: 500; font-size: 18px;">
            {{ $client->name }}
          </div>
        </div>
        <div class="card-body">
          <form action="{{route('admin.whatsapp_clients.update', $client->id)}}" method="post">
            @method('PUT')
            @csrf
            <div class="mb-3">
              <label for="base_url">URL base</label>
              <input type="url" name="base_url" class="form-control" value="{{ $client->base_url }}" required>
            </div>
            <div class="mb-3">
              <label for="access_token">Token de acceso</label>
              <input type="text" name="access_token" class="form-control" value="{{ $client->access_token }}" required>
            </div>
            <div class="mb-3">
              <label for="instance_id">ID instancia encomiendas</label>
              <input type="text" name="delivery_instance_id" class="form-control" value="{{ $client->delivery_instance_id }}" required>
            </div>
            <div class="mb-3">
              <label for="instance_id">ID instancia comunidad</label>
              <input type="text" name="comunity_instance_id" class="form-control" value="{{ $client->comunity_instance_id }}" required>
            </div>
            <div class="mb-3">
              <label for="instance_id">ID instancia masívos</label>
              <input type="text" name="batch_instance_id" class="form-control" value="{{ $client->batch_instance_id }}">
            </div>
            <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" value="1" name="enabled" id="checkbox-{{$client->id}}" {{ $client->enabled ? 'checked' : '' }}>
              <label class="form-check-label" for="checkbox-{{$client->id}}">
                Habilitado
              </label>
            </div>
            <div class="d-flex">
              <button type="submit" class="w-100 justify-content-center btn btn-primary ms-auto">
                Actualizar
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>
@endsection