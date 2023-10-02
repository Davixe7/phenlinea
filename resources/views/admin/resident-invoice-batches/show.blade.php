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
      </tr>
    </thead>
    <tbody>
      @foreach($resident_invoice_batch->resident_invoices as $invoice)
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
      </tr>
      @endforeach
    </tbody>
  </table>

  <a href="/resident-invoices/upload" class="btn btn-primary d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; border-radius: 50%; position: fixed; bottom: 18px; right: 18px;">
    <i class="material-symbols-outlined">add</i>
  </a>
</div>
@endsection