@extends('layouts.app', ['title'=>'Historico de Encomiendas'])
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
  <div class="table-responsive" style="max-width: 420px; margin: 0 auto;">
    <table class="table">
      <thead>
        <th>Foto</th>
        <th>Apto</th>
        <th>Fecha</th>
        <th>ID</th>
      </thead>
      <tbody>
        @foreach($deliveries as $delivery)
        <tr>
          <td>
            @if( $url = $delivery->getFirstMediaUrl('picture') )
            <a href="{{ $url }}" target="_blank">
              <img src="{{ $url }}" class="avatar"/>
            </a>
            @else
            <div class="picture-placeholder"></div>
            @endif
          </td>
          <td>
            <a href="{{ route('extensions.edit', ['extension'=>$delivery->extension_id]) }}">
              {{ $delivery->extension->name }}
            </a>
          </td>
          <td>{{ $delivery->created_at }}</td>
          <td>{{ $delivery->id }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
