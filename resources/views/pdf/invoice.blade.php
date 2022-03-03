<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Factura PHENLINEA</title>
    <style>
      body {
        background: #efefef;
        font-size: 14px;
        font-family: Ubuntu;
      }
      .container {
        background: #fff;
        padding: 15px;
      }
      h1 {
        font-size: 18px;
      }
      h2 {
        font-size: 16px;
      }
      .phenlinea-info {
        text-align: center;
        border-bottom: 3px solid #000;
        padding-bottom: 15px;
        margin-bottom: 15px;
      }
      .phenlinea-info > div {
        vertical-align: top;
      }
      .client-info > div {
        display: table-row;
        padding: 10px 0;
      }
      .client-info > div > b {
        display: table-cell;
        padding: 0px 15px 5px 5px;
      }
      table {
        border-collapse: collapse;
        font-size: 11px;
        width: 100%;
      }
      th {
        background: #efefef;
        border: 1px solid #000;
        padding: 5px;
      }
      th, td {
        text-align: center;
        border: 1px solid #000;
      }

      .cuentas-footer b {
        width: 130px;
        display: inline-block;
      }
      .cuentas-footer > div {
        margin-bottom: 10px;
      }
    </style>
  </head>
  <body>
    <div class="container" style="width: 960px; margin: 0 auto;">
      <div class="phenlinea-info">
        <div style="display: inline-block; width: 24%; border-right: 1px solid #000;">
          <img src="https://phenlinea.com/img/logo.png" alt="" style="width: 80px; margin-bottom: 15px;">
          <div>
            Tel.3144379170
          </div>
          <div>
            contabilidad@phenlinea.com
          </div>
        </div>
        <div style="display: inline-block; width: 49%; border-right: 1px solid #000;">
          <h1>PHENLINEA SAS</h1>
          <h2>NIT 901394830-5</h2>
          <h2>Responsable del impuesto sobre las ventas - IVA</h2>
          <div>
            CL 49 B 76 42
          </div>
          <div>
            Medellín, Antioquia - CO
          </div>
        </div>
        <div style="display: inline-block; width: 24%; border-right: 1px solid #000;">
          <div>
            <b>Responsabilidad Fiscal</b>
          </div>
          <div>
            R-99-PN - No responsable
          </div>
        </div>
      </div>

      <div>
        <div style="display: inline-block; width: 49%; vertical-align: top;" class="client-info">
          <div>
            <b>Cliente:</b> {{ $invoice->name }}
          </div>
          <div>
            <b>NIT ó CC:</b> {{ $invoice->nit }}
          </div>
          <div>
            <b>PERSONA:</b> Jurídica
          </div>
          <div>
            <b>DIRECCIÓN:</b> {{ $invoice->address }}
          </div>
          <div>
            <b>CIUDAD:</b> {{ $invoice->city }}
          </div>
          <div>
            <b>CORREO:</b> {{ $invoice->email }}
          </div>
          <div>
            <b>TELEFONO: {{ $invoice->phone }}</b>
          </div>
        </div>

        <div style="display: inline-block; width: 49%; vertical-align: top;" class="client-info">
          <div>
            <b>Factura Electrónica de Venta</b> {{ $invoice->number }}
          </div>
          <div>
            <b>Fecha Creación:</b> 11-Dec-2020 21:35
          </div>
          <div>
            <b>Fecha Validación:</b> 11-Dec-2020 21:36
          </div>
          <div>
            <b>Fecha Vencimiento:</b> 18-Dec-2020
          </div>
          <div>
            <b>Forma Pago:</b> Contado
          </div>
          <div>
            <b>Medio Pago:</b> Efectivo
          </div>
        </div>
      </div>

      <div style="text-align: center; padding: 10px 0;">
        Modalidad: Electrónica - Número de Autorización: 18764001262766 desde A1 hasta A1500 Vigencia: 2020-07-27 al 2021-07-27
      </div>

      <div style="border-bottom: 3px solid #000; padding-bottom: 15px; margin-bottom: 15px;">
        <table>
          <thead>
            <th>
              Número
            </th>
            <th>
              Código
            </th>
            <th>
              Descripción
            </th>
            <th>
              U/M
            </th>
            <th>
              Cantidad
            </th>
            <th>
              Val Unit
            </th>
            <th>
              Impto
            </th>
            <th>
              Dcto
            </th>
            <th>
              Valor Total
            </th>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td></td>
              <td>SERVICIO   DE   PLATAFORMA   WEB   CITOFONIA(MES   DEDICIEMBRE )</td>
              <td>Unidad</td>
              <td>1.00</td>
              <td>$ 150,000.00</td>
              <td>0.0%</td>
              <td>0.0</td>
              <td>$ 150,000.00</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div style="text-align: center; border-bottom: 3px solid #000; padding-bottom: 15px; margin-bottom: 15px;">
        <h3>Información adicional</h3>
        <p style="font-size: 11px;">
          FAVOR CONSIGNAR EN LA CUENTA DE AHORROS No 360-000004-84 BANCOLOMBIA (Art 476 E.T Servicios excluidos del impuesto sobre las ventas #21 )
        </p>
        <p style="font-size: 11px;">
          Esta factura se asimila en todos sus efectos a una letra de cambio de conformidad con el Art. 774 del código de comercio. Autorizo que en caso deincumplimiento de esta obligación sea reportado a las centrales de riesgo, se cobraran intereses por mora.
        </p>
      </div>

      <div>
        <div style="display: inline-block; width: 69%; text-align: center; vertical-align: top;">
          <div style="display: inline-block; padding: 0 15px; vertical-align: top;">
            <b>Valor en letras</b>
            <p>
              CIENTO CINCUENTA MIL PESOSM/CTE
            </p>
          </div>
          <div style="display: inline-block; padding: 0 15px; vertical-align: top;">
            <b>Resumen de Impuestos</b>
            <table>
              <thead>
                <th>Impuesto</th>
                <th>Base gravable</th>
                <th>Valor tributo</th>
              </thead>
              <tbody>
                <td>Sin impuesto</td>
                <td>$ 150,000.00</td>
                <td>$ 0.00</td>
              </tbody>
            </table>
          </div>
        </div>
        <div style="display: inline-block; width: 29%; vertical-align: top;" class="cuentas-footer">
          <div>
            <b>SUBTOTAL</b> $ 150,000.00
          </div>
          <div>
            <b>DESCUENTOS (-)</b> $ 0.00
          </div>
          <div>
            <b>PRECIO BASE</b> $ 150,000.00
          </div>
          <div>
            <b>IMPUESTOS (+)</b> $ 0.00
          </div>
          <div>
            <b>ANTICIPO (-)</b> $ 0.00
          </div>
          <div>
            <b>TOTAL (-)</b> $ 150,000.00
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
