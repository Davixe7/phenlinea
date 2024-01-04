@extends('layouts.super')
@section('content')
<div class="container">
  <div class="table-responsive">
    <table class="table">
      <thead>
        <th>Fecha</th>
        <th>Admin</th>
        <th>Resumen</th>
        <th>Estado</th>
        <th>Acciones</th>
      </thead>
      <tbody>
        @foreach( $messages as $message )
        <tr>
          <td>{{ $message->created_at }}</td>
          <td>
            <a href="/admin/admins/{{ $message->admin_id }}" target="_blank">
              {{ $message->admin->name }}
            </a>
          </td>
          <td>{{ $message->body }}</td>
          <td>
            <div class="badge">
              {{ ['taken'=>'enviado', 'pending'=>'pendiente'][$message->status] }}
            </div>
          </td>
          <td>_</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection

@push('styles')
<style>
  .badge {
    padding: .25rem .5rem;
    border-radius: .25rem;
    color: green !important;
    background: lightgreen;
    width: fit-content;
  }
</style>
@endpush