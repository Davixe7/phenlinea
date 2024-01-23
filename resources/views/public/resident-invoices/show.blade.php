@extends('layouts.public')
@section('styles')
<style>
  body {
    background: #faf7fb;
  }
  hr {
    margin: 10px 0 !important;
  }
  #factura {
    font-size: 13px;
    padding: 20px;
    margin: 20px auto;
    border: 1px solid #efefef;
    background: #fff;
  }
  #factura .header {
    display: flex;
  }
  #factura .header .logo-wrap {
    background: #fff;
    flex: 0 0 150px;
    margin-right: 15px;
    padding: 10px;
    border: 1px solid lightgray;
    overflow: hidden;
  }
  #factura .header .header-info {
    flex: 1 1 auto;
  }
  #factura h1 {
    font-size: 24px;
    margin-bottom: 0;
  }

  .extension-number {
    font-size: 15px;
    font-weight: 600;
  }

  .billing-dates {
    display: flex;
  }
  .billing-dates > div {
    flex: 1 1 auto;
  }
  .billing-account {
    margin-bottom: 15px;
    padding: 7px;
    border: 1px solid #efefef;
  }
  .billing-table th {
    border-bottom-width: 1px !important;
  }
  .billing-table th:first-child {
    border-left: 1px solid #efefef;
  }
  .billing-table th:last-child {
    border-right: 1px solid #efefef;
  }
  .billing-table td, .billing-table th {
    text-align: right;
  }
  .billing-table td:first-child, .billing-table th:first-child {
    text-align: left;
  }
</style>
@endsection
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-6 mx-auto">
      <div id="factura">
        <div class="header">
          <div class="logo-wrap">
            @if( false )
              <img src="/{{ $resident_invoice->resident_invoice_batch->admin->picture }}" style="width: 100%;">
            @else
              <img src="{{ asset('img/logo.png') }}" style="width: 100%;">
            @endif
          </div>
          <div class="header-info">
            <div class="d-flex">
              <h1>Cuenta de cobro</h1>
              <div class="bill-id ml-auto">
                <b>#{{ str_pad($resident_invoice->id,4,'0',STR_PAD_LEFT) }}</b>
              </div>
            </div>
            <div class="unit-name">
              <b>{{ $resident_invoice->resident_invoice_batch->admin->name }}</b>
            </div>
            <div class="phone">
              {{ $resident_invoice->resident_invoice_batch->admin->phone }}
            </div>
            <div class="address">
              {{ $resident_invoice->resident_invoice_batch->admin->address }}
            </div>
            <div class="email">
              {{ $resident_invoice->resident_invoice_batch->admin->email }}
            </div>
          </div>
        </div>

        <hr/>

        <div class="billing-info">
          <div class="extension-info">
            <div class="extension-number">
              Referente de pago: {{ $resident_invoice->resident_invoice_batch->admin_id }}-{{ $resident_invoice->apto }}
            </div>
            <div class="extension-owner">
              {{ $resident_invoice->extension->residents->count() ? $resident_invoice->extension->residents->first()->name : '' }}
              {{-- Juan Ignacio Restrepo Restrepo | Sonia Contanza Restrepo Zapata --}}
            </div>
          </div>

          <hr>

          <div class="billing-dates">
            <div>
              <b>Período</b>
              <br>
              {{ $resident_invoice->periodo_es }}
            </div>
            <div>
              <b>Emitido</b><br>
              {{ $resident_invoice->emision_es }}
            </div>
            <div>
              <b>Pagar antes de</b><br>
              {{ $resident_invoice->limite_es }}
            </div>
          </div>

          <hr>
        </div>

        <div class="billing-table">
          <table class="table table-sm table-bordered">
            <thead>
              <th>Concepto</th>
              <th>Vencido</th>
              <th>Actual</th>
            </thead>
            <tbody>
              @for ($i = 1; $i < 7; $i++)
              @if( !$resident_invoice->{"concepto$i"} ) @continue @endif
              <tr>
                <td>{{ $resident_invoice->{"concepto$i"} ?: 0 }}</td>
                <td>{{ $resident_invoice->{"vencido$i"}  ?: 0 }}</td>
                <td>{{ $resident_invoice->{"actual$i"}   ?: 0 }}</td>
              </tr>
              @endfor
              </tr>
              <tr>
                <td colspan="2">
                  <b>Total Valor a Pagar</b>
                </td>
                <td>
                  <b style="color: green;">
                      {{ $resident_invoice->total }}
                  </b>
                </td>
              </tr>
            </tbody>
          </table>
          
          @if( $resident_invoice->note )
            <div class="billing-account d-flex mt-3">
              {{ $resident_invoice->note }}
            </div>
          @endif

          <div class="py-3 d-flex">
            @if( $resident_invoice->resident_invoice_batch->link )
              <button class="btn btn-link ms-auto me-3" href="{{ $resident_invoice->link }}">
                Pagar online
              </button>
            @endif
            <a class="btn btn-primary" href="{{ route('resident-invoices.download', ['resident_invoice' => $resident_invoice->id]) }}">
              Descargar
            </a>
            <!-- Recuerde reportar sus pagos al correo facturacion@gerenciamospropiedad.com -->
          </div>

        </div>
      </div><!-- Close bill -->
    </div>
  </div>
</div>
@endsection
