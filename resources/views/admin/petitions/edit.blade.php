@extends('layouts.app')
@section('styles')
<style>
    .table-responsive h1 {
        padding: 30px;
    }
    .card-body {
        padding: 0 30px;
    }
    .form-group {
        margin-bottom: 35px;
    }
    .material-form .form-group label {
        display: block;
        font-size: 14px;
        margin-bottom: 10px;
    }
    .material-form .form-group .form-control {
        font-size: 16px;
        padding: 0;
        border: none;
        border-bottom: 1px solid #000;
        border-radius: 0;
        background: none;
    }
    .material-form .form-group .form-control:focus {
        border-bottom: 1px solid var(--primary);
        box-shadow: none;
    }
    .navbar.navbar-light {
        background: #4B7094;
        color: #fff;
    }
    .navbar.navbar-light a {
        color: #fff !important;
    }
</style>
@endsection
@section('content')
<div class="container" style="max-width: 480px;">
    <div class="table-responsive mt-5">
        <div class="d-flex align-center justify-space-between">
            <h1>
                Detalle del PQRS
            </h1>
            <div class="px-5">
                <b>
                    {{ str_pad( $pqrs->id, 4, '0', STR_PAD_LEFT ) }}
                </b>
            </div>
        </div>
        <div class="card-body material-form">
            <form action="{{ route('prqs.update', ['petition'=>$pqrs->id]) }}" method="POST">
                @method('PUT')
                <div class="form-group">
                <label>Unidad Residencial</label>
                <input class="form-control" type="text" value="{{ $pqrs->admin->name }}" disabled>
            </div>
            <div class="form-group">
                <label>Nombre y apellidos</label>
                <input class="form-control" type="text" name="name" value="{{ $pqrs->name }}">
            </div>
            <div class="row">
                <div class="form-group col-lg-6">
                    <label>Teléfono 1</label>
                    <input class="form-control" type="tel" name="phone" value="{{ $pqrs->phone }}">
                </div>
                <div class="form-group col-lg-6">
                    <label>Teléfono 2 (opcional)</label>
                    <input class="form-control" type="tel" name="phone_2" value="{{ $pqrs->phone_2 }}">
                </div>
            </div>
            <div class="form-group">
                <label>Descripción</label>
                <textarea class="form-control" name="description" required>{{ $pqrs->description }}</textarea>
            </div>
            <div class="form-group row">
                @foreach( $pqrs->getMedia('attachments') as $media)
                <div class="col-4 col-lg-3">
                    <img src="{{ $media->original_url }}" style="width: 100%;">
                </div>
                @endforeach
            </div>
            @csrf
            </form>
        </div>
    </div>
</div>
@endsection