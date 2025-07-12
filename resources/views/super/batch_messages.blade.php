@extends('layouts.super')
@section('content')
<div class="container-fluid">
  <div class="table-responsive">
    <table class="table">
      <thead>
        <th>ID</th>
        <th>Fecha</th>
        <th>Asunto</th>
        <th>Admin</th>
        <th>Estado</th>
        <th>Acciones</th>
      </thead>
      <tbody>
        @foreach( $messages as $message )
        <tr>
          <td>{{ $message->id }}</td>
          <td>{{ $message->created_at }}</td>
          <td>{{ $message->title }}</td>
          <td>
            <a href="/admin/admins/{{ $message->admin_id }}" target="_blank">
              {{ $message->admin->name }}
            </a>
          </td>
          <td>
            <div class="badge">
              {{ $statuses[$message->status] }}
            </div>
          </td>
          <td>
            <form
              action="{{ route('admin.batch_messages.delete', ['batch_message'=>$message->id]) }}"
              method="post">
              @csrf
              @method('DELETE')
              <button
                onclick="window.confirm('Seguro que desea eliminar el mensaje?') ? event.target.parentNode.submit() : '', false"
                class="btn"
                type="button">
                <i class="material-symbols-outlined" style="pointer-events: none;">delete</i>
              </button>
            </form>
          </td>
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