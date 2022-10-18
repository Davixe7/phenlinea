@extends('layouts.app')
@section('content')
<div class="container">
  <div class="table-responsive">
    <h1>
      WhatsApp - Mensajería general
    </h1>
    <div class="text-secondary">
      {{ auth()->user()->whatsapp_instance_id }}
    </div>
    @if( isset( $base64 ) )
      <img src="{{ $base64 }}" alt="">
    @endif
  </div>
</div>
@endsection