@extends('layouts.app')

@push('styles')
<style>
  .card-header {
    background: #008069;
    color: #fff;
    padding: 1rem;
  }
  .card-footer {
    background: #f0f2f5;
  }
  .outgoing {
    font-family: Segoe UI,Helvetica Neue,Helvetica,Lucida Grande,Arial,Ubuntu,Cantarell,Fira Sans,sans-serif;
    font-size: 14.2px;
    max-width: 320px;
    box-shadow: 0 1px .5px rgba(11,20,26,.13);
    padding: .75rem;
    padding-bottom: 1px;
    border-radius: 10px;
    background: #d9fdd3;
  }
  .card-body {
    position: relative;
  }
  .overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
    opacity: .4;
    background-image: url('/img/whatsapp-bg.png');
    background-repeat: repeat;
  }
</style>
@endpush

@section('content')
<div class="col-lg-5 mx-auto pt-4">

  <div class="alert alert-danger">
    Estimado usuario, existe un mensaje masivo pendiente de envío,
  </div>
  <!-- para poder continuar enviando mensajes necesita tomar una decisión con respecto a este mensaje. -->

  <div class="card" style="background: #efeae2">
    <div class="card-header">
      {{ $message->title }}
    </div>
    <div class="card-body">
      <div class="overlay"></div>
      <div style="position: relative; z-index: 1;">
        <div class="outgoing">
          {!! Str::markdown($message->body) !!}
        </div>
        <div>
          {{ $message->getFirstMediaUrl('attachment') }}
        </div>
      </div>
    </div>
    <div class="card-footer d-flex">
      <a
        style="flex: 1 1 auto;"
        href="#"
        onclick="window.confirm('Seguro de eliminar el mensaje') ? document.querySelector('#deleteForm').submit() : ''" class="btn btn-outline-danger me-3">
        Eliminar
      </a>
      <a
        style="flex: 1 1 auto;"
        href="{{ route('batch-messages.create', ['pending_adviced'=>1]) }}" class="btn btn-primary">
        Validar
      </a>
    </div>
    <form id="deleteForm" action="{{ route('batch-messages.destroy', ['batch_message'=>$message->id]) }}" method="POST">
        @csrf
        @method('DELETE')
      </form>
  </div>
</div>
@endsection