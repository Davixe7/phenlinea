@extends('layouts.app', ['title'=>'Facturas'])

@section('styles')
<style>
  .pill {
    display: inline-flex;
    align-items: center;
    padding: .25rem .75rem;
    font-size: .8rem;
    font-weight: 400;
    background: lightgray;
    color: #242424;
    border: none;
    text-transform: capitalize;
    text-decoration: none;
  }

  .pill-pagado {
    background: lightgreen;
    color: green;
  }

  .pill-vencido {
    background: #fdb2b2;
    color: #dd0808;
  }
</style>
@endsection

@section('content')
<div class="container">
  <div class="row">
    <div class="col-lg-9">
      <div class="table-responsive">
        @if( $invoices->count() )
        <table class="table">
          <thead>
            <th>Fecha</th>
            <th>Total</th>
            <th>Estado</th>
            <th>Fecha de pago</th>
            <th>Acciones</th>
          </thead>
          <tbody>
            @foreach( $invoices as $invoice )
            <tr>
              <td>{{ $invoice->date }}</td>
              <td>{{ $invoice->total }}</td>
              <td>
                <div class="pill pill-{{ $invoice->status }}">
                  {{ $invoice->status }}
                </div>
              </td>
              <td>
                @if( $invoice->paid_at )
                {{ \Carbon\Carbon::parse( $invoice->paid_at ) }}
                @endif
              </td>
              <td>
                <div class="btn-group">
                  @if( $invoice->status != 'pagado' )
                  <a id="facturasDropdown" class="nav-link" target="blank" href="https://winred.co/payment/invoice-wallet.jsp?step=1&pId=108">
                    Pagar | <img src="https://corbanca.com.co/wp-content/uploads/2022/06/pse.png" style="width: 100px;">
                  </a>
                  @endif
                  <a href="{{ route('invoices.show', ['invoice'=>$invoice->id]) }}" class="btn btn-link btn-sm">
                    VER FACTURA
                  </a>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @else
        <div class="alert alert-info">
          <i class="material-symbols-outlined mr-3">
            info
          </i>
          No hay registros disponibles de facturas para su unidad
        </div>
        @endif
      </div>
    </div>
    <div class="col-lg-3">
      <div class="pill">
        <i class="material-symbols-outlined me-2">arrow_circle_down</i>
        <a href="{{ asset('storage/documentos/RUT.pdf') }}" target="_blank" download>
          RUT
        </a>
      </div>
      <div class="pill">
        <i class="material-symbols-outlined me-2">arrow_circle_down</i>
        <a href="{{ asset('storage/documentos/CERTIFICADO_BANCARIO.pdf') }}" target="_blank" download>
          Certificado Bancario
        </a>
      </div>
      <div class="pill">
        <i class="material-symbols-outlined me-2">arrow_circle_down</i>
        <a href="{{ asset('storage/documentos/SEGURIDAD_SOCIAL.pdf') }}" target="_blank" download>
          Seguridad Social
        </a>
      </div>
    </div>
  </div>
</div>
@endsection