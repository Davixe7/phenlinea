@extends('layouts.app')

@section('styles')
<style>
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
    <div class="col-md-6 mt-3 mx-auto">
      <h1>
        Importar facturas
      </h1>

      <ul class="list-group px-0">
        <li class="list-group-item">
          <h6>
            1. Descargar formato
          </h6>
          <div class="d-flex align-items-start">
            <p style="flex: 0 1 auto; margin-right: 10px;">
              Descargue el formato requerido y cargue los datos al archivo XLSX
            </p>
            <v-btn dark success color="success" class="btn btn-success" style="flex: 1 0 auto;" href="{{ asset('phenlinea_formato_factura.xlsx') }}">
              <i class="material-icons mr-2">arrow_circle_down</i>
              Descargar Formato
            </v-btn>
          </div>
        </li>
        <li class="list-group-item">
          <h6>
            2. Cargar XLSX
          </h6>
          <form action="{{ route('facturas.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row mb-2">
              <div class="form-group col-md-4">
                <label for="#" class="mb-2">
                  Periodo
                </label>
                <input type="date" name="periodo" class="form-control" value="" required>
              </div>
              <div class="form-group col-md-4">
                <label for="#" class="mb-2">
                  Emisión
                </label>
                <input type="date" name="emision" class="form-control" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
              </div>
              <div class="form-group col-md-4">
                <label for="#" class="mb-2">
                  Pagar antes de
                </label>
                <input type="date" name="limite" class="form-control" required>
              </div>
            </div>
            <div class="form-group">
              <label for="">URL de Pago Online</label>
              <input type="url" class="form-control" name="link" placeholder="https://enlacebancario.com">
            </div>
            
            <div class="form-group">
              <label for="">Notas</label>
              <textarea rows="4" class="form-control" name="note" placeholder="Escribe alguna nota para las facturas"></textarea>
            </div>
            
            <div class="form-group d-flex align-items-center">
              <input
                required
                type="file"
                name="file"
                class="form-control mr-3"
                style="border: 2px solid green; height: 43px;">
              <v-btn type="submit" primary  dark class="btn btn-primary">
                Importar facturas
              </v-btn>
            </div>
          </form>
        </li>
      </ul>
      <!-- <div class="card" style="box-shadow: none; border: 1px solid lightgray;">
        <div class="card-body text-center">
          <form action="{{ route('facturas.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row text-left">
              <div class="form-group col-md-4">
                <label for="#" class="mb-2">
                  <i class="material-icons">calendar_today</i>
                  Periodo
                </label>
                <input type="date" name="periodo" class="form-control" value="" required>
              </div>
              <div class="form-group col-md-4">
                <label for="#" class="mb-2">
                  <i class="material-icons">calendar_today</i>
                  Emisión</label>
                <input type="date" name="emision" class="form-control" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
              </div>
              <div class="form-group col-md-4">
                <label for="#" class="mb-2">
                  <i class="material-icons">calendar_today</i>
                  Pagar antes de</label>
                <input type="date" name="limite" class="form-control" required>
              </div>
            </div>
            <div class="form-group d-flex align-items-center">
              <input type="file" class="form-control mr-3" name="file" required>
              <v-btn type="submit" primary  dark class="btn btn-primary">
                Subir
              </v-btn>
            </div>
          </form>
        </div>
      </div> -->
    </div>
  </div>
</div>
@endsection
