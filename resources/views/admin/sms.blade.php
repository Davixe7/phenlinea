@extends('layouts.app')
@section('content')
@php
  $types = [
    'admin'    => 'administración',
    'delivery' => 'encomienda',
    'services' => 'servicios',
  ];
@endphp
<div class="container">
  <div class="table-responsive">
    <h1>
      Historico SMS desde Portería
    </h1>
    <table class="table">
      <thead>
        <th>Enviado el</th>
        <th>Apto</th>
        <th>Tipo</th>
      </thead>
      <tbody>
        @foreach($messages as $message)
          <tr>
            <td>{{ $message->created_at }}</td>
            <td>{{ $message->extension->name }}</td>
            <td>{{ $types[ $message->type ] }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection