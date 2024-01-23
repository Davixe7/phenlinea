@push('styles')
  <style>
    .page-title {
      font-size: 18px;
      font-weight: 500;
      margin: 0;
    }
    td, td * {
      white-space: nowrap;
    }
    .table--balances td:last-child {
      width: fit-content;
      padding: 0;
      text-align: center;
      border-left: 1px solid #efefef !important;
    }
    .form-control[type=date] {
      flex: 1 0 100%;
      margin-bottom: .5rem;
      width: initial;
    }
    @media(min-width: 991px){
      .form-control[type=date] {
        flex: 1 0 auto;
        margin-bottom: 0;
      } 
    }
  </style>
@endpush

@push('scripts')
<script>
  setTimeout(function(){
    const filters        = document.querySelector('.filters')
    const filtersToggler = document.querySelector('#filtersToggler')

    filtersToggler.addEventListener('click', toggleFilters)

    function toggleFilters(){
      console.log('Toggling')
      filters.classList.toggle('d-none')
    }
  }, 2000)
</script>
@endpush
<div class="card mb-3">
  <div class="card-body d-flex align-items-center">
    <h1 class="page-title">
      Estado de cuenta
    </h1>

    <div class="d-flex ms-auto">
      <button class="btn btn-round--sm me-2" id="filtersToggler">
        <i class="material-symbols-outlined">filter_alt</i>
      </button>
      <button
        class="btn btn-link text-danger d-none d-sm-inline-flex"
        href="{{ route('extensions.balance', ['extension'=>$extension, 'download'=>1]) }}">
        Descargar PDF
      </button>
      <button
        class="btn btn-round--sm text-danger d-inline-flex d-sm-none"
        href="{{ route('extensions.balance', ['extension'=>$extension, 'download'=>1]) }}"
        style="font-size: 1rem;">
        <i class="material-symbols-outlined d-sm-none">download</i>
      </button>
    </div>
  </div>
</div>

<div class="filters card d-none mb-3">
  <div class="card-body">
    <form action="{{ $endpoint }}" method="POST">
      @csrf
      <div class="d-flex align-items-center flex-wrap">
        <input type="date" class="form-control w-fit ms-auto me-3" name="startdate" value="{{ $startdate }}" />
        <input type="date" class="form-control w-fit me-3" name="enddate" value="{{ $enddate }}" />
        <input type="hidden" name="apto" value="{{ $extension->name }}" />
        <input type="hidden" name="nit"  value="{{ $extension->admin->nit }}" />
        <button type="submit" class="btn btn-primary ms-auto">
          Actualizar
        </button>
      </div>
    </form>
  </div>
</div>

<div class="table-responsive">
  <table class="table table--balances">
    <thead>
      <th>Nro. Factura</th>
      <th>Periodo</th>
      <th>Limite</th>
      <th style="text-align: center;">Valor</th>
    </thead>
    <tbody>
      @foreach( $invoices as $invoice )
      <tr>
        <td>{{ $invoice->formatted_id }}</td>
        <td>{{ $invoice->periodo_es }}</td>
        <td>{{ $invoice->limite_es }}</td>
        <td style="text-align: center;">
          $ {{ $invoice->total }}  
        </td>
      </tr>
      @foreach($invoice->resident_invoice_payments as $payment)
      <tr class="text-red">
        <td>PAGO</td>
        <td></td>
        <td>{{ $payment->created_at->format('Y-m-d') }}</td>
        <td style="text-align: center;">
          -$ {{ $payment->amount }}
        </td>
      </tr>
      @endforeach
      @endforeach
      <tr>
        <td colspan="2"></td>
        <td>Total</td>
        <td style="text-align: center;">
          $ {{ $total }}
        </td>
      </tr>
    </tbody>
  </table>
</div>