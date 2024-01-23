<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Factura</title>
    <style>
      body {
        background: #faf7fb;
        font-size: 14px;
        line-height: 1rem;
      }
      #factura {
        padding: 20px;
        margin: 20px auto;
        border: 1px solid #efefef;
        background: #fff;
        width: 420px;
      }
      .logo-wrap {
        box-sizing: border-box;
        padding: 10px 20px;
        border: 1px solid lightgray;
        background: #fff;
      }
      .logo-wrap img {
        width: 100%;
        object-fit: contain;
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
      .table {
        width: 100%;
        margin-bottom: 10px;
        padding: 5px 0;
        border: 1px solid rgba(0,0,0,.09);
        border-left: none;
        border-right: none;
        border-collapse: collapse;
      }
      .billing-dates td {
        padding-bottom: 7.5px;
      }
      .billing-dates th {
        text-align: left;
        padding-top: 7.5px;
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
        <table class="table header">
          <tbody>
            <tr>
              <td style="width: 50%;">
                <div class="logo-wrap">
                  @if( $resident_invoice->resident_invoice_batch->admin->getFirstMediaPath('picture') )
                    <img src="{{ $resident_invoice->resident_invoice_batch->admin->getFirstMediaPath('picture') }}" style="width: 100%;">
                  @else
                    <img src="{{ public_path('img/logo.png') }}" style="width: 100%;">
                  @endif
                </div>
              </td>
              <td style="padding: 10px;">
                <div style="line-height: 1.05rem;">
                  <div><h1 style="margin-bottom: 4px;">Cuenta de cobro</h1></div>
                  <div><b>#{{ str_pad($resident_invoice->id,4,'0',STR_PAD_LEFT) }}</b></div>
                  <div><b>{{ $resident_invoice->resident_invoice_batch->admin->name }}</b></div>
                  <div>{{ $resident_invoice->resident_invoice_batch->admin->phone }}</div>
                  <div>{{ $resident_invoice->resident_invoice_batch->admin->address }}</div>
                  <div>{{ $resident_invoice->resident_invoice_batch->admin->email }}</div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <table class="table" style="border: none">
          <tr>
            <td style="font-size: 15px; font-weight: bold; vertical-align: middle;">
              Referente de pago: {{ $resident_invoice->resident_invoice_batch->admin_id }}-{{ $resident_invoice->apto }} <br>
              {{ $resident_invoice->extension->residents->count() ? $resident_invoice->extension->residents->first()->name : '' }}
            </td>
          </tr>
        </table>

        <table class="table billing-dates">
          <thead>
            <th>Período</th>
            <th>Emitido</th>
            <th>Pagar antes de</th>
          </thead>
          <tbody>
            <tr>
              <td>{{ $resident_invoice->periodo_es }}</td>
              <td>{{ $resident_invoice->limite_es }}</td>
              <td>{{ $resident_invoice->emision_es }}</td>
            </tr>
          </tbody>
        </table>

        @if( $resident_invoice->note )
        <div class="billing-account">
          {{ $resident_invoice->note }}
        </div>
        @endif

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
              @foreach ($resident_invoice->resident_invoice_items as $item)
              <tr>
                <td style="text-align: left; padding: 8px 5px;"> {{ $item->title }}</td>
                <td style="text-align: right; padding: 8px 5px;">{{ $item->pending }}</td>
                <td style="text-align: right; padding: 8px 5px;">{{ $item->current }}</td>
              </tr>
              @endforeach
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
