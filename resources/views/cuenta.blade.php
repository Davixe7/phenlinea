<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
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
  </style>
</head>

<body>

  <div class="container">
    <table style="width: 100%; margin-bottom: 1.5rem;">
      <tr>
        <td style="width: 50%; text-align: center;">
          <div>{{ $admin->name }}</div>
          <div>Nit. {{ $admin->nit }}</div>
          <div>www.phenlinea.com</div>
          <div>Cel. {{ $admin->phone }}</div>
          <div>{{ $admin->email }}</div>
          <div>{{ $admin->address }}</div>
        </td>
        <td style="width: 50%;">
          <table style="margin-left: auto;">
            <thead>
              <th>Fecha de emisión</th>
              <th>Fecha de suspensión</th>
            </thead>
            <tbody>
              <tr>
                <td>2023/11/06</td>
                <td></td>
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
            <td>{{ $admin->name }}</td>
          </tr>
          <tr>
            <th>NIT</th>
            <td>{{ $admin->nit }}</td>
          </tr>
          <tr>
            <th>Dirección</th>
            <td>{{ $admin->address }}</td>
          </tr>
        </tbody>
      </table>
      <table>
        <tbody>
          <tr>
            <th>Correo</th>
            <td>{{ $admin->email }}</td>
          </tr>
          <tr>
            <th>Teléfono</th>
            <td>{{ $admin->phone }}</td>
          </tr>
          <tr>
            <th>Encargado</th>
            <td>{{ $admin->address }}</td>
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
        @foreach( range(1,13) as $row)
        <tr>
          <td>A2821</td>
          <td>EDIFICO PALO ALTO P.H </td>
          <td>2023/01/09</td>
          <td>Enero</td>
          <td>$ 135.000</td>
        </tr>
        @endforeach
        <tr>
          <td colspan="3"></td>
          <td>Total</td>
          <td>$ 135.000</td>
        </tr>
      </tbody>
    </table>
  </div>

</body>

</html>