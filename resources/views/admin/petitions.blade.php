@extends('layouts.app')
@section('styles')
<style>
  table.table td:nth-child(4) {
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 30ch;
    white-space: nowrap;
  }
</style>
@endsection
@section('content')
<div class="container">
  <div class="table-responsive">
    <h1>
      Solicitudes
    </h1>
    <table class="table">
      <thead>
        <th>
          Fecha
        </th>
        <th>
          Apartamento
        </th>
        <th>
          Título
        </th>
        <th>
          Descripción
        </th>
        <th>
          Estado
        </th>
        <th>
          Acciones
        </th>
      </thead>
      <tbody>
        @foreach($petitions as $petition)
        <tr>
          <td>
            {{ $petition->created_at }}
          </td>
          <td>
            {{ $petition->extension->name }}
          </td>
          <td>
            {{ $petition->title }}
          </td>
          <td>
            {{ $petition->description }}
          </td>
          <td>
            {{ $petition->status }}
          </td>
          <td>
            <div class="btn-group">
              <button class="btn btn-sm btn-link" onclick="window.confirm('Seguro que desea aprobar la solicitud?') ? document.querySelector('#petition-aprove-form-{{$petition->id}}').submit() : ''">
                <i class="material-icons" title="aprobar">done_all</i>
              </button>
              <button class="btn btn-sm btn-link" onclick="window.confirm('Seguro que desea denegar la solicitud?') ? document.querySelector('#petition-deny-form-{{$petition->id}}').submit() : ''">
                <i class="material-icons" title="denegar">remove_done</i>
              </button>
              <button class="btn btn-sm btn-link" onclick="window.confirm('Seguro que desea eliminar la solicitud?') ? document.querySelector('#petition-destroy-form-{{$petition->id}}').submit() : ''">
                <i class="material-icons" title="eliminar">delete</i>
              </button>
              <a href="{{ route('petitions.show', ['petition'=>$petition->id]) }}" class="btn btn-sm btn-link">
                <i class="material-icons" title="detalles">visibility</i>
              </a>
            </div>
            <form
              action="{{ route('petitions.destroy', ['petition'=>$petition->id]) }}"
              id="petition-destroy-form-{{$petition->id}}"
              method="post">
              @csrf
              @method('DELETE')
            </form>
            <form
              action="{{ route('petitions.update', ['petition'=>$petition->id]) }}"
              id="petition-aprove-form-{{$petition->id}}"
              method="post">
              @csrf
              @method('PUT')
              <input type="hidden" name="status" value="approved">
            </form>
            <form
              action="{{ route('petitions.update', ['petition'=>$petition->id]) }}"
              id="petition-deny-form-{{$petition->id}}"
              method="post">
              @csrf
              @method('PUT')
              <input type="hidden" name="status" value="denied">
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection