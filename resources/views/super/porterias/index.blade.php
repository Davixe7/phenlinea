@extends('layouts.super')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-lg-8">
      <div class="table-responsive">
        <h1>
          Porterias
        </h1>
        <table class="table">
          <thead>
            <th>
              Unidad
            </th>
            <th>
              Nombre
            </th>
            <th>
              Email
            </th>
            <th>
              aptos.
            </th>
            <th>
              Acciones
            </th>
          </thead>
          <tbody>
            @foreach( $porterias as $porteria )
            <tr>
              <td>{{ $porteria->admin->name }}</td>
              <td>{{ $porteria->name }}</td>
              <td>{{ $porteria->email }}</td>
              <td>{{ $porteria->extensions_count }}</td>
              <td>
                <div class="btn-group">
                  <a class="btn btn-sm btn-link" href="{{ route('admin.porterias.edit', ['porteria'=>$porteria->id]) }}">
                    <i class="material-icons">edit</i>
                  </a>
                  <button class="btn btn-sm btn-link" onclick="window.confirm('seguro de eliminar la porteria?') ? document.querySelector('#porteria-delete-form-{{$porteria->id}}') : ''">
                    <i class="material-icons">delete</i>
                  </button>
                  <form id="porteria-delete-form-{{$porteria->id}}" action="{{ route('admin.porterias.destroy', ['porteria'=>$porteria->id]) }}" method="POST">
                    @csrf
                  </form>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card">
        <div class="card-header">
          Registrar porteria
        </div>
        <div class="card-body">
          <form action="{{ route('admin.porterias.store') }}" method="POST">
            @csrf
            <div class="mb-3">
              <label for="admin_id">Unidad</label>
              <select name="admin_id" id="admin_id" class="form-control">
                @foreach($admins as $admin)
                <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="mb-3">
              <label for="name">Nombre</label>
              <input type="text" class="form-control" name="name" required>
            </div>

            <div class="mb-3">
              <label for="email">Email</label>
              <input type="email" class="form-control" name="email" required>
            </div>

            <div class="mb-3">
              <label for="password">Contraseña</label>
              <input type="password" class="form-control" name="password" required>
            </div>

            <button class="btn btn-primary w-100 d-flex justify-content-center">
              Registrar porteria
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection