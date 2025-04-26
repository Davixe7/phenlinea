@extends('layouts.legacy')
@section('content')
<form method="POST" action="{{ route('admin.batch_messages.store') }}">
@csrf
<div class="container-fluid">
<div class="row">
    <div class="col-lg-3">

        @if(!$admin)
        <div class="form-group">
            <label for="admin_id" class="form-label">
                Seleccionar administrador
            </label>
            <select name="admin_id" id="admin_id" class="form-control" required>
                @foreach($admins as $admin)
                <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                @endforeach
            </select>
        </div>
        @else
        <div class="form-group">
            <label for="admin_id" class="form-label">
                Administrador
            </label>
            <select name="admin_id" id="admin_id" class="form-control">
                <option value="{{ $admin->id }}">{{ $admin->name }}</option>
            </select>
        </div>
        @endif

        <div class="form-group">
            <ul class="list-group">
                @foreach( $extensions as $extension )
                <li class="list-group-item">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="{{ $extension->id }}" id="defaultCheck{{$extension->id}}" name="receivers[]">
                        <label class="form-check-label" for="defaultCheck{{$extension->id}}">
                            {{ $extension->name }}
                        </label>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="col-lg-9">
        <label for="admin_id" class="form-label">
            Cuerpo del Mensaje
        </label>
        <textarea class="form-control mb-3" name="content" required></textarea>
        <button class="btn btn-primary" type="submit">
            Enviar
        </button>
    </div>
</div>
</div>
</form>
@endsection

@section('scripts')
<script>
    console.log('Loaded');
    
    const adminSelect = document.querySelector('#admin_id')
    adminSelect.addEventListener('change', (event)=>{
        console.log('changed')
        window.location.href = '/admin/batch_messages/create?admin_id=' + event.target.value;
    })
</script>
@endsection