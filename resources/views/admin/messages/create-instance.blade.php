@extends('layouts.app')
@section('styles')
<style>
  .missing-instance-alert {
    text-align: center;
    line-height: 1.55em;
    height: calc(100vh - 120px);
    display: flex;
    flex-flow: column nowrap;
    align-items: center;
    justify-content: center;
    max-width: 320px;
    margin: 0 auto;
  }
</style>
@endsection

@section('content')
<div class="container">
  @if( auth()->user()->wa_instance_id == null )
    <div class="missing-instance-alert">
      <p>
        Usted no tiene una instancia de WhatsApp asociada a su cuenta
      </p>
      <a
        href="{{ route('messages.createInstance') }}"
        class="btn btn-primary">
        Generar instancia
      </a>
    </div>
    @else
  @endif
</div>
@endsection