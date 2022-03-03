@extends('layouts.app')
@section('content')
<div class="container">
  <h1>Mis facturas</h1>
  @if( $facturas->count() )
  <ul class="list-group px-0 mx-0">
    @foreach( $facturas as $f )
      <li class="list-group-item d-flex align-items-center">
        <i class="material-icons mr-3">calendar_today</i>
        {{ $f->emision }}
        <a href="{{ route('factura.show', ['factura'=>$f->id]) }}" class="ml-auto">VER FACTURA</a>
      </li>
    @endforeach
  </ul>
  @else
  <div class="alert alert-info">
    <i class="material-icons mr-3">info</i>
    No hay registros disponibles de facturas para su apartamento
  </div>
  @endif
</div>
@endsection
