@extends('layouts.public')
@section('styles')
<style>
    input.form-control  {
      font-size: 2rem;
      text-align: center;
    }
    .card-header, .card-footer {
      border-top: 0;
      border-bottom: 0;
      background: #fff;
    }
    .card-header {
      font-size: 20px;
    }
  </style>
@endsection
@section('content')
<div class="container">
    <div class="row">
      <div class="col-lg-4 mx-auto" style="margin-top: 50px;">
        <form action="{{ route('public.resident-invoices') }}" method="POST">
        @csrf
        <div class="card text-center">
          <div class="card-header">
            Consulte su facturas
          </div>
          <div class="card-body">
            <div class="mb-3">
              <label class="form-label" for="#">NIT</label>
              <input type="tel" class="form-control" name="nit">
            </div>
            <div class="mb-3">
              <label class="form-label" for="#">Número de apartamento</label>
              <input type="tel" class="form-control" name="apto">
            </div>
          </div>
          <div class="card-footer d-flex justify-content-end">
            <button class="btn btn-primary">Consultar</button>
          </div>
        </div>
        </form>
      </div>
    </div>
  </div>
@endsection