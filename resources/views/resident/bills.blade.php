@extends('layouts.app')
@section('content')
<div class="container">
  <h1>
    Enlaces de pago
  </h1>
  <div class="row">
    @foreach($bills as $bill)
    <div class="col-lg-4">
      <div class="card">
        <div class="card-body">
          <h2 class="mb-2">
            {{ $bill->title }}
          </h2>
          <div>
            <a href="{{ $bill->url }}" target="_blank" class="btn btn-primary text-white d-inline-block text-center w-100">
              Ir al sitio
            </a>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>
@endsection