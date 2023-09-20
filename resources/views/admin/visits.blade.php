@extends('layouts.app', ['title'=>'Visitantes'])
@section('styles')
<style>
  .picture-placeholder,
  .avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: grey;
  }
</style>
@endsection
@section('content')
<div class="container">
  <div class="table-responsive">
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
          <td>
            @if( $url = $visit->visitor->getFirstMediaUrl('picture') )
            <a href="{{ $url }}" target="_blank">
              <img src="{{ $url }}" class="avatar"/>
            </a>
            @else
            <div class="picture-placeholder"></div>
            @endif
          </td>
          <td>{{ $visit->extension_name }}</td>
          <td>{{ $visit->visitor->dni }}</td>
          <td>{{ $visit->visitor->name }}</td>
          <td>{{ $visit->visitor->company }}</td>
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
