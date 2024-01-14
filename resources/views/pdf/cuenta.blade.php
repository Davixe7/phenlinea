<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Estado de cuenta</title>
  <style>
    body {
      background: #4f4f4f;
      font-family: sans-serif;
      font-size: 14px;
    }
    table {
      border: 1px solid #000;
      border-collapse: collapse;
    }

    th,
    td {
      text-align: center;
      border: 1px solid #000;
      padding: .25rem;
    }

    .container {
      width: 825px;
      padding: 1.5rem;
      margin: 0 auto;
      background: #fff;
    }

    #client-info {
      display: flex;
    }

    #client-info > table {
      width: 50%;
    }
    #client-info th, #client-info td {
      text-align: left;
    }

    td:nth-child(4) {
      text-transform: capitalize;
    }
    .text-red {
      color: red;
    }
  </style>
</head>

<body>

  <div class="container">
    <table style="width: 100%; margin-bottom: 1.5rem;">
      <tr>
        <td style="width: 50%; text-align: center;">
          <div>{{ $extension->admin->name }}</div>
          <div>Nit. {{ $extension->admin->nit }}</div>
          <div>www.phenlinea.com</div>
          <div>Cel. {{ $extension->admin->phone }}</div>
          <div>{{ $extension->admin->email }}</div>
          <div>{{ $extension->admin->address }}</div>
        </td>
        <td style="width: 50%;">
          <table style="margin-left: auto;">
            <thead>
              <th>Fecha de emisión</th>
              <th>Fecha de suspensión</th>
            </thead>
            <tbody>
              <tr>
                <td>{{ now() }}</td>
                <td>{{ now()->endOfMonth() }}</td>
              </tr>
            </tbody>
          </table>
        </td>
      </tr>
    </table>
    <div id="client-info" style="margin-bottom: 1.5rem">
      <table>
        <tbody>
          <tr>
            <th>Cliente</th>
            <td>{{ $extension->owner_name }}</td>
          </tr>
          <tr>
            <th>NIT</th>
            <td>{{ $extension->owner_phone }}</td>
          </tr>
          <tr>
            <th>Dirección</th>
            <td>{{ $extension->admin->address }}</td>
          </tr>
        </tbody>
      </table>
      <table>
        <tbody>
          <tr>
            <th>Correo</th>
            <td>{{ $extension->admin->email }}</td>
          </tr>
          <tr>
            <th>Teléfono</th>
            <td>{{ $extension->admin->phone }}</td>
          </tr>
          <tr>
            <th>Encargado</th>
            <td>{{ $extension->admin->address }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <table style="width: 100%; text-align: center;">
      <thead>
        <th>Nro. Factura</th>
        <th>Cliente</th>
        <th>Fecha</th>
        <th>Mes</th>
        <th>Valor</th>
      </thead>
      <tbody>
        @foreach( $invoices as $invoice )
        <tr>
          <td>{{ $invoice->formatted_id }}</td>
          <td>{{ $extension->owner_name }}</td>
          <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
          <td>{{ $invoice->created_at->translatedFormat('F') }}</td>
          <td>
            <div style="width: 40%; margin: 0 auto; text-align: left;">
              $ {{ $invoice->total }}</td>
            </div>
        </tr>
          @foreach($invoice->resident_invoice_payments as $payment)
            <tr class="text-red">
              <td>PAGO</td>
              <td>{{ $extension->owner_name }}</td>
              <td>{{ $payment->created_at->format('Y-m-d') }}</td>
              <td>{{ $payment->created_at->translatedFormat('F') }}</td>
              <td>
                <div style="width: 40%; margin: 0 auto; text-align: left;">
                  -$ {{ $payment->amount }}
                </div>
              </td>
            </tr>
          @endforeach
        @endforeach
        <tr>
          <td colspan="3"></td>
          <td>Total</td>
          <td>
            <div style="width: 40%; margin: 0 auto; text-align: left;">
              $ {{ $total }}
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

</body>

</html>