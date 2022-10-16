@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-lg-9">
      <div class="table-responsive">
        <h1>Mis facturas</h1>
        @if( $invoices->count() )
        <table class="table">
          <thead>
            <th>Fecha</th>
            <th>Total</th>
            <th>Estado</th>
            <th>Acciones</th>
          </thead>
          <tbody>
            @foreach( $invoices as $i )
            <tr>
              <td>{{ $i->date }}</td>
              <td>{{ $i->total }}</td>
              <td>pendiente</td>
              <td>
                <div class="btn-group">
                  <a href="{{ route('invoices.show', ['invoice'=>$i->id]) }}" class="btn btn-link btn-sm">
                    <i class="material-icons">remove_red_eye</i>
                  </a>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @else
        <div class="alert alert-info">
          <i class="material-icons mr-3">
            info
          </i>
          No hay registros disponibles de facturas para su unidad
        </div>
        @endif
      </div>
    </div>
    <div class="col-lg-3">
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
  </div>
</div>
@endsection