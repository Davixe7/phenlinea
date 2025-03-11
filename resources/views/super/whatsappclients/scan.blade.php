@extends('layouts.super')
@push('styles')
<style>
  body {
    background:#939597;
  }
  .form-control {
    color: rgba(0, 0, 0, .7);
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

  .btn {
    width: fit-content !important;
    height: fit-content !important;
  }

  .input-mono {
    font-size: 18px;
    font-weight: 500;
    text-align: center;
    letter-spacing: .55rem;
    font-family: monospace, sans-serif !important;
  }

  .float-label {
    position: relative;
    margin-top: 1.5rem;
  }

  .float-label label {
    font-size: .8rem;
    position: absolute;
    top: 0;
    left: 0;
    transform: translateY(-100%) translateX(.25rem);
  }
  .w-100 {
    width: 100% !important;
  }
  .page-title {
    color: #404040;
    font-size: 18px;
    font-weight: 500;
    margin: 0;
  }
</style>
@endpush
@section('content')
<div class="container">
  <div class="row">
    <div class="col-lg-3 mx-auto d-flex flex-column align-items-center">
      <div class="card" style="min-width: 360px;">
        <div class="card-header pa-md bg-white">
        <div class="page-title">
        Vincular instancia: 
        {{ $labels[$instance_type] }}
        </div>
        </div>
        <div class="card-body">
          <div class="form-group float-label">
            <input class="form-control input-mono" readonly value="{{ $instance_id }}" style="font-size: .9rem; letter-spacing: .5rem; text-align: center;">
            <label>ID de instancia</label>
          </div>
          <hr>
          @if (request()->scanMethod == 'pairingCode')
          <div class="form-group float-label">
            <input class="form-control input-mono" readonly value="{{ $pairing_code }}">
            <label>Codigo de emparejamiento</label>
          </div>
          @else
          <img src="{{ $base64 }}" alt="">
          @endif

          <button onclick="document.querySelector('#instanceForm').submit()"
            class="btn btn-primary d-inline-block w-100">
            Asignar Instancia
          </button>
          <form id="instanceForm" method="POST"
            action="{{ route('admin.whatsapp_clients.update', ['whatsapp_client' => $whatsapp_client->id]) }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="{{ $instance_type }}" value="{{ $instance_id }}">
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection