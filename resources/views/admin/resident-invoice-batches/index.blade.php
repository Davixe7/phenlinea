@extends('layouts.app', ['title'=>'Facturación de residentes'])
@section('styles')
<style>
  th, td {
    white-space: nowrap;
  }
  th {
    border-bottom: 1px solid rgba(0,0,0,.097) !important;
  }
</style>
@endsection

@section('content')
@if( !count( $resident_invoice_batches ) )
  <div style="height: calc(100vh - 200px); display: flex; align-items: center; justify-content: center;">
    <div class="text-center">
      <img src="{{ asset('img/empty_invoices.png') }}" alt="" class="mb-3">
      <p>No hay facturas existentes</p>
      <a
        href="/resident-invoice-batches/upload"
        class="btn btn-primary">
        Cargar lote de facturas
      </a>
    </div>
  </div>
@else
<div class="table-responsive">
  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Período</th>
        <th>Emisión</th>
        <th>Limite</th>
        <th>Facturas</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach($resident_invoice_batches as $batch)
      <tr>
        <td>{{ $batch->id }}</td>
        <td>{{ $batch->periodo }}</td>
        <td>{{ $batch->emision }}</td>
        <td>{{ $batch->limite }}</td>
        <td>{{ $batch->resident_invoices_count }}</td>
        <td>
          <div class="btn-group">
            <a href="{{ route('resident_invoice_batches.show', $batch) }}" class="btn btn-link">
              <i class="material-symbols-outlined">remove_red_eye</i>
            </a>
          </div>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <a
    href="/resident-invoice-batches/upload"
    class="btn btn-primary d-flex align-items-center justify-content-center"
    style="width: 60px; height: 60px; border-radius: 50%; position: fixed; bottom: 18px; right: 18px;">
    <i class="material-symbols-outlined">add</i>
  </a>
</div>
@endif
@endsection