@extends('layouts.super')

@section('styles')
<style>
  .file-input {
    border: 2px solid green;
    height: 43px;
  }

  button {
    color: #fff !important;
    word-wrap: normal;
    white-space: nowrap;
  }

  label {
    font-size: .9em;
    font-weight: 500;
  }

  h6 {
    padding: 20px;
    margin: -12px -20px 20px -20px;
    border-bottom: 1px solid lightgray;
  }

  .list-group-item {
    padding-bottom: 30px;
  }
  .pill {
      display: inline-block;
      padding: .25rem .75rem;
      font-size: .8rem;
      font-weight: 400;
      background: lightgray;
      color: #242424;
      border: none;
  }
  .pill-pagado {
      background: lightgreen;
      color: green;
  }
  .fab {
      width: 60px;
      height: 60px;
      border: none;
      border-radius: 50%;
      outline: none;
      position: fixed;
      bottom: 30px;
      right: 30px;
      color: #fff;
      background: #3490dc;
  }
</style>
@endsection

@section('content')
<button class="fab" @click="importing = !importing">
    <i class="material-icons">add</i>
</button>
<div class="container py-3">
  <div class="row">
    <div class="col">
      <div class="table-responsive">
        <h1>
          Facturas del mes
        </h1>
        <div class="container-fluid">
          <form @submit.prevent="fetchInvoices">
            <div class="row">
              <div class="col mb-2">
                <select name="month" class="form-control" v-model="month">
                  <option v-for="(monthName, i) in monthsName" :value="i+1">
                    @{{ monthName }}
                  </option>
                </select>
              </div>
              <div class="col">
                <select name="year" v-model="year" class="form-control mb-2">
                  <option value="2022">2022</option>
                </select>
              </div>
              <div class="col mb-2">
                <button type="submit" class="btn btn-secondary w-100 justify-content-center">
                    Consultar
                </button>
              </div>
            </div>
          </form>
        </div>
        <table class="table" v-if="invoices && invoices.length">
          <thead>
            <th>Numero</th>
            <th>NIT</th>
            <th>Unidad</th>
            <th>Estado</th>
            <th>Total</th>
            <th>Pagado el</th>
            <th>Accion</th>
          </thead>
          <tbody>
            <tr v-for="invoice in invoices" :key="invoice.id">
              <td>
                @{{ invoice.number }}
              </td>
              <td>
                @{{ invoice.admin.nit }}
              </td>
              <td>
                @{{ invoice.admin.name }}
              </td>
              <td>
                <div class="pill" :class="{'pill-pendiente': invoice.status == 'pendiente', 'pill-pagado': invoice.status == 'pagado' }">
                    @{{ invoice.status }}    
                </div>
              </td>
              <td>
                @{{ invoice.total }}
              </td>
              <td>
                  @{{ invoice.paid_at }}
              </td>
              <td>
                  <select
                    :disabled="(invoice.payment_method =='pse' && invoice.status == 'pagado')"
                    v-model="invoice.status"
                    @change="updateInvoice(invoice)"
                    class="form-control form-control-sm">
                      <option :value="'pagado'">Pagado</option>
                      <option :value="'pendiente'">Pendiente</option>
                  </select>
              </td>
            </tr>
          </tbody>
        </table>
        <div v-else class="alert alert-info text-center">
          No hay resultados disponibles
        </div>
      </div>
    </div>

    <div class="col" :class="{'d-none': !importing}">
      @if(Session::has('message'))
      <div class="alert alert-success d-flex align-items-center">
        <span>
          {{ Session::get('message') }}
        </span>
        <i class="material-icons ml-auto">
          done_all
        </i>
      </div>
      @endif

      <ul class="list-group px-0">
        <li class="list-group-item">
          <h6>
            Cargar XLSX
          </h6>
          <form action="{{ route('admin.invoices.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <div class="col">
                <div class="form-group mb-2">
                  <label for="month" class="mb-2">Mes</label>
                  <select name="month" class="form-control" required>
                    @foreach($monthsName as $monthName)
                    <option value="{{ $loop->iteration }}" @if( $month==$loop->iteration ) selected @endif>
                      {{ $monthName }}
                    </option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col">
                <div class="form-group mb-2">
                  <label for="year" class="mb-2">Año</label>
                  <select name="year" class="form-control" required>
                    <option value="2022">2022</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="#">Archivo Excel</label>
              <input type="file" name="file" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 justify-content-center">
              Importar facturas
            </button>
          </form>
        </li>
      </ul>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.7.13/dist/vue.js"></script>
<script>
    const app = new Vue({
        el: '#app',
        data: {
            invoices: [],
            month: 0,
            year: 2022,
            monthsName: [],
            importing: false
        },
        methods: {
            updateInvoice(invoice){
                if( !window.confirm('seguro que desea actualizar el estado de la factura?') ) return
                axios.post('/admin/invoices/' + invoice.id, {status: invoice.status, '_method': 'PUT'})
                .then(response => {
                    this.invoices.splice( this.invoices.indexOf( invoice ), 1, response.data.data )
                })
            },
            fetchInvoices(){
                let data = { year: this.year, month: this.month }
                axios.get('/admin/invoices/upload', {params: data})
                .then(response=>{
                    this.invoices = Object.values(response.data.data)
                })
            }
        },
        mounted(){
            this.month = {!! json_encode( $month, true ) !!}
            this.monthsName = {!! json_encode( $monthsName, true ) !!}
            this.invoices = Object.values({!! json_encode( $invoices, true ) !!})
        }
    })
</script>
@endsection