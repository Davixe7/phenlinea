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
              {{ $message->status == 'taken' ? 'enviado' : 'pendiente'}}
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

@push('style')
<style>
  .badge {
    padding: 0 .5rem;
    border-radius: .5rem;
    color: green;
    background: lightgreen;
    width: fit-content;
  }
</style>
@endpush