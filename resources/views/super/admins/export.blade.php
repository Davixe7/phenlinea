@extends('layouts.app')
@section('content')
  <div class="container" style="box-shadow: none;">
    <div class="col-md-5 mx-auto">
      <h1>Exportar Excel</h1>
      <div style="color: #919191;">
        <p>Descargara un reporte de todas las extensiones y residentes asociados a una unidad, en un archivo excel en formato XLSX</p>
      </div>
      <residents-export :admins="{{ json_encode($admins) }}"/>
    </div>
  </div>
@endsection