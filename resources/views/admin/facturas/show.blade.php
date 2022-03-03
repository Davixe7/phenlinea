@extends('layouts.app')
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
            @if( $factura->admin->picture )
              <img src="/{{ $factura->admin->picture }}" style="width: 100%;">
            @else
              <img src="{{ public_path('img/logo.png') }}" style="width: 100%;">
            @endif
          </div>
          <div class="header-info">
            <div class="d-flex">
              <h1>Cuenta de cobro</h1>
              <div class="bill-id ml-auto">
                <b>#{{ str_pad($factura->id,4,'0',STR_PAD_LEFT) }}</b>
              </div>
            </div>
            <div class="unit-name">
              <b>{{ $factura->admin->name }}</b>
            </div>
            <div class="phone">
              {{ $factura->admin->phone }}
            </div>
            <div class="address">
              {{ $factura->admin->address }}
            </div>
            <div class="email">
              {{ $factura->admin->email }}
            </div>
          </div>
        </div>

        <hr/>

        <div class="billing-info">
          <div class="extension-info">
            <div class="extension-number">
              Referente de pago: {{ $factura->admin_id }}-{{ $factura->apto }}
            </div>
            <div class="extension-owner">
              {{ $factura->apartment->residents->count() ? $factura->apartment->residents->first()->name : '' }}
              {{-- Juan Ignacio Restrepo Restrepo | Sonia Contanza Restrepo Zapata --}}
            </div>
          </div>

          <hr>

          <div class="billing-dates">
            <div>
              <b>Período</b>
              <br>
              {{ $factura->periodo_es }}
            </div>
            <div>
              <b>Emitido</b><br>
              {{ $factura->emision_es }}
            </div>
            <div>
              <b>Pagar antes de</b><br>
              {{ $factura->limite_es }}
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
              @if( !$factura->{"concepto$i"} ) @continue @endif
              <tr>
                <td>{{ $factura->{"concepto$i"} ?: 0 }}</td>
                <td>{{ $factura->{"vencido$i"}  ?: 0 }}</td>
                <td>{{ $factura->{"actual$i"}   ?: 0 }}</td>
              </tr>
              @endfor
              </tr>
              <tr>
                <td colspan="2">
                  <b>Total Valor a Pagar</b>
                </td>
                <td>
                  <b style="color: green;">
                      {{ $factura->total }}
                  </b>
                </td>
              </tr>
            </tbody>
          </table>
          
          @if( $factura->note )
            <div class="billing-account d-flex mt-3">
              {{ $factura->note }}
            </div>
          @endif

          <div class="py-3 d-flex">
            <v-btn href="{{ route('facturas.download', ['factura' => $factura->id]) }}">
              Descargar
            </v-btn>
            @if( $factura->link )
              <v-btn href="{{ $factura->link }}" dark class="ml-auto">
                Pagar online
              </v-btn>
            @endif
            <!-- Recuerde reportar sus pagos al correo facturacion@gerenciamospropiedad.com -->
          </div>

        </div>
      </div><!-- Close bill -->
    </div>
  </div>
</div>
@endsection
