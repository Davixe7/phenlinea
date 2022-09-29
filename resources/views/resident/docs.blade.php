@extends('layouts.app')
@section('content')
<div class="container">
  <h1>
    Manuales y documentos
  </h1>
  <hr>
  <ul class="row">
  @foreach( $docs as $doc )
  <li class="col-lg-4">
    <div class="card post-card">
      <div class="card-body">
        <div class="post-header">
          <h2>{{ $doc->title }}</h2>
          <div class="btn-group dropdown">
            <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown">
              <i class="material-icons">more_vert</i>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
              <button type="button" class="dropdown-item" onclick="if( confirm('¿Seguro que desea eliminar la publicacion?') ){ document.querySelector('#delete-post-{{ $doc->id }}-form').submit() }">
                Eliminar
              </button>
              <form action="{{ route('docs.destroy', ['post'=>$doc->id]) }}" method="POST" id="delete-post-{{$doc->id}}-form">
                @csrf
                @method('DELETE')
              </form>
              {{--<a href="#" class="dropdown-item">Actualizar</a>--}}
            </div>
          </div>
        </div>
        <div class="post-date" style="text-transform: capitalize;">
          {{ $doc->created_at->formatLocalized('%d %B %Y %H:%M') }}
        </div>
        <div class="post-description">
          {{ $doc->description }}
        </div>
        @if( $doc->attachments && count($doc->attachments) )
        <small>
          Archivos adjuntos
        </small>
        <div class="d-flex">
          @foreach( $doc->attachments as $attachment )
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
@endsection