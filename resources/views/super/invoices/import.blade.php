@extends('layouts.app')

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
</style>
@endsection

@section('content')
<div class="container">
  <div class="row">
    <div class="col-lg-7">
      <div class="table-responsive">
        <h1>
          Facturas del mes
        </h1>
        <div class="container-fluid">
          <form action="{{ route('admin.invoices.upload') }}">
            <div class="row">
              <div class="col mb-2">
                <select name="month" class="form-control">
                  @foreach($months as $monthName)
                  <option value="{{ $loop->iteration }}" @if( $month==$loop->iteration ) selected @endif>
                    {{ $monthName }}
                  </option>
                  @endforeach
                </select>
              </div>
              <div class="col">
                <select name="year" value="{{ $year }}" class="form-control mb-2">
                  <option value="2022">2022</option>
                </select>
              </div>
              <div class="col mb-2">
                <button class="btn btn-secondary w-100 justify-content-center">Consultar</button>
              </div>
            </div>
          </form>
        </div>
        @if( $invoices && $invoices->count() )
        <table class="table">
          <thead>
            <th>NIT</th>
            <th>Unidad</th>
            <th>Total</th>
          </thead>
          <tbody>
            @foreach( $invoices as $invoice )
            <tr>
              <td>
                {{ $invoice->admin->nit }}
              </td>
              <td>
                {{ $invoice->admin->name }}
              </td>
              <td>
                {{ $invoice->total }}
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @else
        <div class="alert alert-info text-center">
          No hay resultados disponibles
        </div>
        @endif
      </div>
    </div>

    <div class="col-lg-5">
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
                    @foreach($months as $monthName)
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