@extends('layouts.legacy')
@section('content')
@if( $errors->any() )
<div class="alert alert-danger">
    No se pudo enviar la notificación
</div>
@endif
<form action="{{ route('push.store') }}" method="POST" id="push-form">
    @csrf
    <div class="row">
    <div class="col-lg-3">
        <div class="card">
            <div class="card-header">
                Seleccionar destinatarios
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item">
                        <label class="d-flex justify-content-between align-items-center mb-0">
                            Seleccionar todo
                            <input type="checkbox" class="form-check" id="select-all" value="0" name="sent_to_all">
                        </label>
                    </li>
                    @foreach($extensions as $extension)
                        <li class="list-group-item">
                            <label class="d-flex justify-content-between align-items-center mb-0">
                                {{ $extension->name }}
                                <input type="checkbox" class="form-check" name="extensions[]" value="{{ $extension->id }}">
                            </label>
                        </li>
                    @endforeach
                </ul>
                @if( $errors->any() )
                <div class="invalid-feedback d-block">
                    Seleccione por lo menos una extension
                </div>
                @endif
            </div>
            <div class="card-footer">
                <div class="alert alert-info">
                    Solo se mostrarán las extensiones que hayan usado y aceptado las notificaciones push desde la aplicación móvil
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header">
                Enviar Push Notifications
            </div>
            <div class="card-body">
                <textarea class="form-control" name="body" rows="3" maxlength="300" id="push-body" required></textarea>
                <div id="charscount" class="form-text">300</div>
            </div>
            <div class="card-footer text-right">
                <button
                    class="btn btn-primary"
                    type="button"
                    onclick="if( confirm('Confirmo el envío de esta notificación') ){ document.querySelector('#push-form').submit() }">
                    Enviar
                </button>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                Historial de envío
            </div>
            <div class="card-body">
                <ul class="list-group">
                    @foreach( $logs as $log)
                    <li class="list-group-item d-flex">
                        <div class="counter-circle bg-success" style="flex-shrink: 0; width: 30px; height: 30px; background: blue; color: #fff; font-weight: 500; margin-right: 15px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            {{ $log->receivers_count }}
                        </div>
                        <div>
                            <div class="mb-2">
                            {{ $log->body }}
                            </div>
                        {{--<div>
                            {{ $log->created_at }} | Enviado a todos: {{ $log->sent_to_all == '1' ? 'Sí' : 'No' }}
                        </div>--}}
                        </div>
                        {{--<div class="ml-auto">
                            <button type="button" class="btn btn-link btn-sm" onclick="if( confirm('¿Seguro que desea eliminar el registro?') ){ document.querySelector('#log-form-{{ $log->id }}').submit() }">
                                <i class="material-icons">delete</i>
                            </button>
                        </div>--}}
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
</form>
@foreach($logs as $log)
<form method="POST" action="{{ route('push.destroy', ['push_notification_log'=>$log]) }}" id="log-form-{{ $log->id }}">
@csrf
@method('DELETE')
</form>

@endforeach
@endsection

@section('scripts')
<script>
    const selectAllCheckbox = document.querySelector('#select-all')
    const pushBodyInput = document.querySelector('#push-body')
    
    selectAllCheckbox.addEventListener('click', function(e){
        selectAllCheckbox.value = selectAllCheckbox.value == '1' ? 0 : 1
        document.querySelectorAll('.form-check').forEach(function(checkbox){
            checkbox.checked = e.target.checked
        })
    })
    
    pushBodyInput.addEventListener('input', function(e){
        let charsleft = 300 - e.target.value.length;
        document.querySelector('#charscount').innerHTML = charsleft;
    });
</script>
@endsection