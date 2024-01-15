<div class="table-responsive">

  <form action="{{ $endpoint }}">
    <div class="d-flex align-items-center p-3">
      <div style="font-size: 16px; font-weight: 500; white-space: nowrap; margin-right: 1rem;">
        Estado de cuenta
      </div>
      <input type="date" class="form-control w-fit ms-auto me-3" name="startdate" value="{{ $startdate }}" />
      <input type="date" class="form-control w-fit me-3" name="enddate" value="{{ $enddate }}" />
      <input type="hidden" name="apto" value="{{ $extension->name }}" />
      <input type="hidden" name="nit"  value="{{ $extension->admin->nit }}" />
      <button type="submit" class="btn btn-link">
        Actualizar
      </button>
    </div>
  </form>

  <table class="table">
    <thead>
      <th>Nro. Factura</th>
      <th>Periodo</th>
      <th>Limite</th>
      <th>Emision</th>
      <th style="text-align: center;">Valor</th>
    </thead>
    <tbody>
      @foreach( $invoices as $invoice )
      <tr>
        <td>{{ $invoice->formatted_id }}</td>
        <td>{{ $invoice->periodo_es }}</td>
        <td>{{ $invoice->limite_es }}</td>
        <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
        <td>
          <div style="width: 50%; margin: 0 auto; text-align: left;">
            $ {{ $invoice->total }}
        </td>
      </tr>
      @foreach($invoice->resident_invoice_payments as $payment)
      <tr class="text-red">
        <td>PAGO</td>
        <td></td>
        <td></td>
        <td>{{ $payment->created_at->format('Y-m-d') }}</td>
        <td>
          <div style="width: 50%; margin: 0 auto; text-align: left;">
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
          <div style="width: 50%; margin: 0 auto; text-align: left;">
            $ {{ $total }}
          </div>
        </td>
      </tr>
    </tbody>
  </table>

  <div class="d-flex px-3">
    <a
      href="{{ route('extensions.balance', ['extension'=>$extension, 'download'=>1]) }}"
      class="btn btn-danger ms-auto"
      style="font-size: 1rem;">
      Descargar PDF
    </a>
  </div>
</div>