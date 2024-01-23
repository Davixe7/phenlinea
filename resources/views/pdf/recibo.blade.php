<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recibo de caja</title>
  <style>
    body 
    {
      background-color: #4f4f4f;
    }
    * {
     margin: 0;
     padding: 0;
     font-family: sans-serif; 
     font-size: 10px;
    }
    h4 {
      padding: .5rem 0;
    }
    table {
      width: 100%;
      background-color: #fff;
    }
    td:first-child {
      width: 50%;
      text-align: right;
    }
    td:last-child {
      width: 50%;
      text-align: left;
    }

    .info td {
      padding: .05rem .25rem;
    }

    .details {
      border: 1px solid #000;
      border-collapse: collapse;
    }
    .details td, .details th {
      border: 1px solid #000;
      text-align: left !important;
      padding: .25rem;
    }
  </style>
</head>
<body>
  <table style="width: 450px; margin: 0 auto; border: 1px solid #000;">
    <tr>
      <td>
        <table style="border-bottom: 1px solid #000;">
          <tr>
            <td>
              <div>{{ $payment->resident_invoice->extension->admin->name }}</div>
              <div>Nit: {{ $payment->resident_invoice->extension->admin->nit }}</div>
              <div>{{ $payment->resident_invoice->extension->admin->address }}</div>
              <div>{{ $payment->resident_invoice->extension->admin->phone }}</div>
            </td>
            <td style="text-align: center;">
              <img src="{{ public_path('img/logo.png') }}" alt="" style="width: 75px;">
            </td>
          </tr>
        </table>

        <h4 style="text-align: center;">Recibo de caja</h4>

        <table class="info">
          <tr>
            <td>Recibo No:</td>
            <td>{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}</td>
          </tr>

          <tr>
            <td>Propietario:</td>
            <td>{{ $payment->resident_invoice->extension->owner_name }}</td>
          </tr>

          <tr>
            <td>Propiedad:</td>
            <td>APTO {{ $payment->resident_invoice->extension->name }}</td>
          </tr>

          <tr>
            <td>Saldo Ant:</td>
            <td>${{ $payment->previous_balance }}</td>
          </tr>

          <tr>
            <td>Pago:</td>
            <td>${{ $payment->amount }}</td>
          </tr>

          <tr>
            <td>Nuevo Saldo:</td>
            <td>${{ $payment->previous_balance - $payment->amount }}</td>
          </tr>

          <tr>
            <td>Fecha pago:</td>
            <td>{{ $payment->date }}</td>
          </tr>
        </table>

        <h4 style="text-align: center;">Detalle conceptos pagados</h4>

        <table class="details">
          <th>Monto</th>
          <th>Concepto</th>
          <tbody>
            @foreach( $payment->resident_invoice_items as $item)
              <tr>
                <td>${{ $item->pivot->amount }}</td>
                <td>{{ $item->title }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>

        <p style="text-align: center;">Copia para el propietario</p>
      </td>
    </tr>
  </table>
</body>
</html>