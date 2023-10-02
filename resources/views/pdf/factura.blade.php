<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Factura</title>
    <style>
      body {
        background: #faf7fb;
      }
      #factura {
        font-size: 13px;
        padding: 20px;
        margin: 20px auto;
        border: 1px solid #efefef;
        background: #fff;
        width: 420px;
      }
      #factura .header .logo-wrap {
        display: inline-block;
        width: 150px;
        margin-right: 15px;
        padding: 10px 20px;
        border: 1px solid lightgray;
        background: #fff;
        overflow: hidden;
      }
      #factura .header .header-info {
        display: inline-block;
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
        display: block;
      }
      .billing-dates > div {
        display: inline-block;
        width: 32%;
      }
      .billing-account {
        margin-bottom: 15px;
        padding: 7px;
        border: 1px solid #efefef;
      }
      .billing-table {
        width: 100%;
      }
      .billing-table th {
        border-bottom: 1px solid !important;
      }
      .billing-table th:first-child {
        border-left: 1px solid #efefef;
      }
      .billing-table th:last-child {
        border-right: 1px solid #efefef;
      }
      .billing-table td, .billing-table th {
        text-align: right;
        border: 1px solid #dee2e6;
      }
      .billing-table td:first-child, .billing-table th:first-child {
        text-align: left;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <div id="factura">
        <div class="header">
          <div class="logo-wrap">
            @if( false && $resident_invoice->resident_invoice_batch->admin->picture )
              <img src="{{ public_path( $resident_invoice->resident_invoice_batch->admin->picture ) }}" style="width: 100%;">
            @else
              <img src="{{ public_path('img/logo.png') }}" style="width: 100%;">
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

        <hr style="margin: 10px 0; border-color: rgba(0, 0, 0, 0.1);">

        <div class="billing-info">
          <div class="extension-info">
            <div class="extension-number">
              Referente de pago: {{ $resident_invoice->resident_invoice_batch->admin_id }}-{{ $resident_invoice->apto }}
            </div>
            <div class="extension-owner">
              {{ $resident_invoice->apartment->residents->count() ? $resident_invoice->apartment->residents->first()->name : '' }}
              {{-- Juan Ignacio Restrepo Restrepo | Sonia Contanza Restrepo Zapata --}}
            </div>
          </div>

          <hr style="margin: 10px 0; border-color: rgba(0, 0, 0, 0.1);">

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

          <hr style="margin: 10px 0; border-color: rgba(0, 0, 0, 0.1);">

          @if( $resident_invoice->note )
          <div class="billing-account">
            {{ $resident_invoice->note }}
          </div>
          @endif
        </div>

        <div class="billing-table">
          <table style="width: 100%; border-collapse: collapse;">
            <thead>
              <tr>
                <th style="padding: 8px 5px;">Concepto</th>
                <th style="padding: 8px 5px;">Vencido</th>
                <th style="padding: 8px 5px;">Actual</th>
              </tr>
            </thead>
            <tbody>
              @for ($i = 1; $i < 7; $i++)
              @if( !$resident_invoice->{"concepto$i"} ) @continue @endif
              <tr>
                <td style="text-align: left; padding: 8px 5px;">{{ $resident_invoice->{"concepto$i"} ?: 0 }}</td>
                <td style="text-align: right; padding: 8px 5px;">{{ $resident_invoice->{"vencido$i"}  ?: 0 }}</td>
                <td style="text-align: right; padding: 8px 5px;">{{ $resident_invoice->{"actual$i"}   ?: 0 }}</td>
              </tr>
              @endfor
              <tr>
                <td colspan="2" style="text-align: left; padding: 8px 5px;">
                  <b>Total Valor a Pagar</b>
                </td>
                <td style="text-align: right;  padding: 8px 5px;">
                  <b style="color: green;">
                    {{ $resident_invoice->total }}
                  </b>
                </td>
              </tr>
            </tbody>
          </table>
          
          @if( $resident_invoice->link )
          <p style="padding: 10px 0 10px 0; text-align: right;">
            <a href="{{ $resident_invoice->link }}">
              Pagar online
            </a>
          </p>
          @endif

        </div>
      </div><!-- Close bill -->
    </div>
  </body>
</html>
