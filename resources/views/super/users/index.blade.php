@extends('layouts.super')
@section('content')
<div class="container">
  <div class="table-responsive">
    <h1>
      Superusuarios
    </h1>
    <table class="table">
      <thead>
        <th>Nombre</th>
        <th>Email</th>
        <th>Acciones</th>
      </thead>
      <tbody>
        @foreach($users as $user)
          <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>
              <div class="btn-group">
                <button class="btn btn-link btn-sm">
                  <i class="material-icons">edit</i>
                </button>
                <button class="btn btn-link btn-sm">
                  <i class="material-icons">delete</i>
                </button>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection