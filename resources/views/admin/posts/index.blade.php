@extends('layouts.app')
@section('styles')
<style>
  .post-card {
      margin-bottom: 1em;
  }
  h2 {
    font-size: 1.2em;
    margin-bottom: 0;
  }
  .post-description {
    margin-bottom: .75em;
  }
  .post-date {
    font-size: .85em;
    font-weight: 500;
    margin-bottom: .5em;
  }
  .post-pictures {
      height: 250px;
      background-size: cover;
      cursor: pointer;
      margin-bottom: 1em;
  }
  .post-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1em;
  }
  .post-header .dropdown-toggle::after {
      display: none !important;
  }
</style>
@endsection
@section('content')
<div class="container">
  <h1>Cartelera</h1>
    <ul class="row" style="list-style-type: none; padding: 0;">
        <li class="col-lg-4 mb-3">
            <div class="card">
        <div class="card-body">
          <div class="form-section-title">
            CREAR PUBLICACIÓN
          </div>
          <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
              @csrf
            <div class="mb-3">
              <input
                placeholder="Título"
                type="text"
                class="form-control"
                name="title"
                required>
            </div>
            <div class="mb-3">
              <textarea
                placeholder="Escribe aquí"
                rows="12"
                class="form-control"
                name="description"
                required></textarea>
            </div>
            <div class="mb-3">
              <label style="font-size: .9em; font-weight: 500;">Imagenes</labe>
              <input type="file" class="form-control" name="pictures" multiple>
            </div>
            <div class="mb-3">
              <label style="font-size: .9em; font-weight: 500;">Archivos</labe>
              <input type="file" class="form-control" name="attachments" multiple>
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
        @foreach( $posts as $post )
        <li class="col-lg-4">
          <div class="card post-card">
        <div class="card-body">
          <div class="post-header">
              <h2>{{ $post->title }}</h2>
              <div class="btn-group dropdown">
                  <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown">
                    <i class="material-icons">more_vert</i>
                  </button>
                  <div class="dropdown-menu dropdown-menu-right">
                    <button
                        type="button"
                        class="dropdown-item"
                        onclick="if( confirm('¿Seguro que desea eliminar la publicacion?') ){ document.querySelector('#delete-post-{{ $post->id }}-form').submit() }">
                        Eliminar
                    </button>
                    <form action="{{ route('posts.destroy', ['post'=>$post->id]) }}" method="POST" id="delete-post-{{$post->id}}-form">
                        @csrf
                        @method('DELETE')
                    </form>
                    {{--<a href="#" class="dropdown-item">Actualizar</a>--}}
                  </div>
                </div>
          </div>
          @if( is_array($post->pictures) && count($post->pictures) )
            <div class="post-pictures" style="background-image: url({{ $post->pictures[0]['url'] }})"></div>
          @endif
          <div class="post-date" style="text-transform: capitalize;">
            {{ $post->created_at->formatLocalized('%d %B %Y %H:%M') }}
          </div>
          <div class="post-description">
            {{ $post->description }}
          </div>
          @if( $post->attachments && count($post->attachments) )
            <small>
                Archivos adjuntos
            </small>
            <div class="d-flex">
                @foreach( $post->attachments as $attachment )
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
