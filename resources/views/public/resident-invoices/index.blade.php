@extends('layouts.public', ['title'=>'Facturación de residentes'])
@section('styles')
<style>
  th,
  td {
    white-space: nowrap;
  }

  th {
    border-bottom: 1px solid rgba(0, 0, 0, .097) !important;
  }
</style>
@endsection

@section('content')
<div class="container">
  <div class="table-responsive">
    <table class="table">
      <thead>
        <tr>
          <th>Apto.</th>
          <th>Concepto</th>
          <th>Vencido</th>
          <th>Actual</th>
          <th>Concepto</th>
          <th>Vencido</th>
          <th>Actual</th>
          <th>Concepto</th>
          <th>Vencido</th>
          <th>Actual</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach($invoices as $invoice)
        <tr>
          <td>{{ $invoice->apto }}</td>

          <td>{{ $invoice->concepto1 }}</td>
          <td>{{ $invoice->vencido1 }}</td>
          <td>{{ $invoice->actual1 }}</td>

          <td>{{ $invoice->concepto2 }}</td>
          <td>{{ $invoice->vencido2 }}</td>
          <td>{{ $invoice->actual2 }}</td>

          <td>{{ $invoice->concepto3 }}</td>
          <td>{{ $invoice->vencido3 }}</td>
          <td>{{ $invoice->actual3 }}</td>

          <td>
            <a href="{{'/detalle-factura/' . $invoice->id}}" target="_blank" class="btn btn-link btn-sm">
              <i class="material-symbols-outlined">open_in_new</i>
            </a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection