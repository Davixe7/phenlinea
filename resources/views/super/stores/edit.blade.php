@extends('layouts.app')
@section('content')
  <div class="container">
    <h1 class="mb-3">Actualizar comercio</h1>
    <edit-store :commerce="{{ json_encode( $store ) }}"/>
  </div>
@endsection
