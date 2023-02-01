@extends('layouts.super')
@section('styles')
<style>
  .form-group label {
    display: block;
    font-size: 1.2em;
    font-weight: 500;
    color: #0075ff;
    margin: 0 0 20px 0 !important;
  }
</style>
@endsection
@section('content')
<div class="container">
  @if( Session::has('message') )
  <div class="alert alert-success">
    {{ Session::get('message') }}
  </div>
  @endif
  <div class="page-title">
    <h1>
      Documentos legales
    </h1>
  </div>
  <div class="row">
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <form action="{{ route('admin.documentos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
              <label for="rut">
                1. RUT
              </label>
              <input type="file" class="form-control-file" name="file" required>
            </div>
            <input type="hidden" name="filename" value="RUT">
            <div class="form-group text-right">
              <v-btn dark type="submit" class="w-100">
                enviar
              </v-btn>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <form action="{{ route('admin.documentos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
              <label for="rut">
                2. SEGURIDAD SOCIAL
              </label>
              <input type="file" class="form-control-file" name="file" required>
            </div>
            <input type="hidden" name="filename" value="SEGURIDAD_SOCIAL">
            <div class="form-group text-right">
              <v-btn dark type="submit" class="w-100">
                enviar
              </v-btn>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <form action="{{ route('admin.documentos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
              <label for="rut">
                3. CERTIFICADO BANCARIO
              </label>
              <input type="file" class="form-control-file" name="file" required>
            </div>
            <input type="hidden" name="filename" value="CERTIFICADO_BANCARIO">
            <div class="form-group text-right">
              <v-btn dark type="submit" class="w-100">
                enviar
              </v-btn>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
