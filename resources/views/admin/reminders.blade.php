@extends('layouts.app')
@section('content')
<div class="container">
  <h1>
    Notificaciones
  </h1>
  <ul class="row" style="list-style-type: none; padding: 0;">
    <li class="col-lg-4 mb-3">
      <div class="card">
        <div class="card-body">
          <div class="form-section-title">
            CREAR NOTIFICACIÓN
          </div>
          <form action="{{ route('reminders.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
              <select placeholder="Apartamento" class="form-control" name="extension_id" required>
                <option value="">Seleccione apartamento</option>
                @foreach($extensions as $extension)
                  <option value="{{ $extension->id }}">
                    {{ $extension->name }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <input placeholder="Título" type="text" class="form-control" name="title" required>
            </div>
            <div class="mb-3">
              <textarea placeholder="Escribe aquí" rows="7" class="form-control" name="description" required></textarea>
            </div>
            <div class="mb-3">
              <label style="font-size: .9em; font-weight: 500;">Imagenes</labe>
                <input type="file" class="form-control" name="pictures" multiple>
            </div>
            <div class="d-flex justify-content-end">
              <button class="btn btn-link" type="submit">
                Publicar
              </button>
            </div>
          </form>
        </div>
      </div>
    </li>

    @foreach( $reminders as $reminder )
    <li class="col-lg-4">
      <div class="card post-card">
        <div class="card-body">
          <div class="post-header">
            <h2>{{ $reminder->title }}</h2>
            <div class="btn-group dropdown">
              <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown">
                <i class="material-icons">more_vert</i>
              </button>
              <div class="dropdown-menu dropdown-menu-right">
                <button type="button" class="dropdown-item" onclick="if( confirm('¿Seguro que desea eliminar la publicacion?') ){ document.querySelector('#delete-post-{{ $reminder->id }}-form').submit() }">
                  Eliminar
                </button>
                <form action="{{ route('reminders.destroy', ['reminder'=>$reminder->id]) }}" method="POST" id="delete-post-{{$reminder->id}}-form">
                  @csrf
                  @method('DELETE')
                </form>
                {{--<a href="#" class="dropdown-item">Actualizar</a>--}}
              </div>
            </div>
          </div>
          @if( is_array($reminder->pictures) && count($reminder->pictures) )
          <div class="post-pictures" style="background-image: url({{ $reminder->pictures[0]['url'] }})"></div>
          @endif
          <div class="post-date" style="text-transform: capitalize;">
            {{ $reminder->created_at->formatLocalized('%d %B %Y %H:%M') }}
          </div>
          <div class="post-description">
            {{ $reminder->description }}
          </div>
          @if( $reminder->attachments && count($reminder->attachments) )
          <small>
            Archivos adjuntos
          </small>
          <div class="d-flex">
            @foreach( $reminder->attachments as $attachment )
            <div>
              <a href="{{ $attachment['url'] }}" target="blank">
                {{ $attachment['name'] }}
              </a>
            </div>
            @endforeach
          </div>
          @endif
        </div>
      </div>
    </li>
    @endforeach
  </ul>
</div>
</div>
@endsection