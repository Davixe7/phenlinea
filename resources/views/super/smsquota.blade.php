@extends('layouts.legacy')
@section('content')
<div class="container">
    <h1 class="page-title">
        Consumo de servicio SMS Masivo
    </h1>
    <div class="section-title mb-3">
        Filtrar por fecha
    </div>
    <form action="{{ route('admin.smsQuota') }}" method="GET">
        <div class="row mb-3">
        <div class="col-lg-4">
            <input class="form-control" type="date" name="dateFrom" placeholder="fecha desde" required>
        </div>
        <div class="col-lg-4">
            <input class="form-control" type="date" name="dateTo" placeholder="fecha hasta" required>
        </div>
        <div class="col-lg-4">
            <button type="submit" class="btn btn-primary w-100 justify-content-center">
                Consultar
            </button>
        </div>
        </div>
    </form>
    <table class="table">
        <thead>
            <th>Administración</th>
            <th>Mensajes enviados</th>
        </thead>
        <tbody>
            @foreach($admins as $admin)
            <tr>
                <td>
                    {{ $admin->name }}
                </td>
                <td>
                    {{ $admin->notifications_count ?: 0}}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection