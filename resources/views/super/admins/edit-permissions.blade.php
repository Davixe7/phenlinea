@extends('layouts.super')
  @section('styles')
    <style>
      .dasboard-section-title {
        margin-left: -45px;
        display: flex;
        align-items: center;
      }
      .dashboard-section-title a {
        margin-right: 15px;
        text-decoration: none;
      }
    </style>
  @endsection
  @section('content')
  <div class="container">
  <div class="row">
    <div class="col-md-6 mx-auto">
      @if( session('message') )
      <div class="alert alert-success">
        {{ session('message') }}
      </div>
      @endif
      <h1>
        <a href="{{ route('admin.admins.index') }}">
          <i class="material-symbols-outlined">
            arrow_back
          </i>
        </a>
        {{ $admin->name }}
      </h1>
      @if( $modules->count() )
      <div class="form-section-title">
        Módulos Disponibles
      </div>
      <form action="{{ route('admin.admins.update-permissions', ['admin'=>$admin->id]) }}" method="POST">
        @csrf
        @method('PUT')
        <ul class="list-group mb-4 p-0">
          @foreach( $modules as $module )
          <li class="list-group-item">

            <div class="custom-control custom-switch">
              <input
                type="checkbox"
                name="modules[]"
                value="{{ $module->id }}"
                id="switch{{ $loop->index }}"
                class="custom-control-input"
                @if( $admin->modules->find( $module->id ) )
                  checked
                @endif
              >
              <label class="custom-control-label" for="switch{{ $loop->index }}">
              {{ $module->name }}
              </label>
            </div>
          </li>
          @endforeach
        </ul>
        <div class="card">
          <div class="card-body">
            <button type="submit" class="btn btn-primary">
              Actualizar
            </button>
          </div>
        </div>
      </form>
      @else
      <div class="alert alert-info">
        No hay módulos registrados aún
      </div>
      @endif
    </div><!-- end col-md-6 -->
  </div><!-- end row -->
</div>
@endsection