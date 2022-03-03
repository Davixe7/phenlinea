@extends('layouts.app')
@section('content')
<div class="container">
  <h1>Mis facturas</h1>
  <div class="d-flex align-items-center">
      <div class="pill">
          <a href="{{ asset('storage/documentos/RUT.pdf') }}" target="_blank" download>
              <i class="material-icons mr-3">arrow_circle_down</i>
              RUT
              </a>
      </div>
      <div class="pill">
          <a href="{{ asset('storage/documentos/CERTIFICADO_BANCARIO.pdf') }}" target="_blank" download>
              <i class="material-icons mr-3">arrow_circle_down</i>
              Certificado Bancario</a>
      </div>
      <div class="pill">
          <a href="{{ asset('storage/documentos/SEGURIDAD_SOCIAL.pdf') }}" target="_blank" download>
              <i class="material-icons mr-3">arrow_circle_down</i>
              Seguridad Social</a>
      </div>
  </div>
  @if( $invoices->count() )
  <ul class="list-group px-0 mx-0">
    @foreach( $invoices as $i )
      <li class="list-group-item d-flex align-items-center">
        <i class="material-icons mr-3">
          calendar_today
        </i>
        {{ $i->date }}
        <a
          href="{{ route('invoices.show', ['invoice' => $i->id]) }}"
          class="ml-auto">
          VER FACTURA
        </a>
      </li>
    @endforeach
  </ul>
  @else
  <div class="alert alert-info">
    <i class="material-icons mr-3">
      info
    </i>
    No hay registros disponibles de facturas para su unidad
  </div>
  @endif
</div>
@endsection
