@extends('layouts.app')
@section('content')
<div class="container">
  <div class="table-responsive">
    <h1>
      Visitas al conjunto residencial
    </h1>
    <table class="table">
      <thead>
        <th>Foto</th>
        <th>Apto</th>
        <th>Cédula</th>
        <th>Nombre</th>
        <th>Empresa</th>
        <th>ARL-EPS</th>
        <th>Entrada</th>
        <th>Salida</th>
      </thead>
      <tbody>
        @foreach($visits as $visit)
          <tr>
            <td>{{ $visit->picture }}</td>
            <td>{{ $visit->apartment }}</td>
            <td>{{ $visit->dni }}</td>
            <td>{{ $visit->name }}</td>
            <td>{{ $visit->plate }}</td>
            <td>{{ $visit->company }}</td>
            <td>{{ $visit->arl_eps }}</td>
            <td>{{ $visit->checkin }}</td>
            <td>{{ $visit->checkout }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection