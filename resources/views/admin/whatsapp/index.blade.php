@extends('layouts.app', ['title'=>'Mensajería Masiva'])
@push('styles')
<style>
  .btn-fab {
    width: 55px;
    height: 55px;
    background: var(--bs-primary);
    color: #fff;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    position: absolute;
    bottom: 40px;
    right: 20px;
  }
</style>
@endpush
@section('content')
<div class="table-responsive" style="max-width: 600px; margin: 0 auto;">
  <h1>Historial de mensajes</h1>
  <table class="table">
    <thead>
      <th>Cant.</th>
      <th>Asunto</th>
      <th>Fecha</th>
      <th>Estado</th>
    </thead>
    <tbody>
      @foreach($batch_messages as $message)
      <tr>
        <td>{{ count(explode(",", $message->numbers)) }}</td>
        <td>{{ $message->title }}</td>
        <td>{{ Carbon\Carbon::parse($message->created_at)->format('Y-m-d H:i') }}</td>
        <td>{{ $statuses[$message->status] }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <a
    href="{{ route('batch-messages.create') }}"
    class="btn btn-primary btn-fab">
    <i class="material-symbols-outlined">add</i>
  </a>
</div>
@endsection