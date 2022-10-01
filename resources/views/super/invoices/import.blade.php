@extends('layouts.super')

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
      <h1>
        Importar facturas
      </h1>
      <ul class="list-group px-0">
        <li class="list-group-item">
          <h6>
            Cargar XLSX
          </h6>
          <form action="{{ route('admin.invoices.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-2">
                <label for="#" class="mb-2">
                    Periodo
                </label>
              <input type="date" name="periodo" class="form-control" value="" required>
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
    </div>
  </div>
</div>
@endsection
