@extends('layouts.app')
@section('content')
<div class="container">
  @if( isset($novelty) )
  <div class="row">
    <div class="col-lg-6">
      <div class="card post-card">
        <div class="card-body">
          <div class="post-header">
            <h2>{{ $novelty->title }}</h2>
          </div>
          @if( is_array($novelty->pictures) && count($novelty->pictures) )
          <div class="post-pictures" style="background-image: url({{ $novelty->pictures[0]['url'] }})"></div>
          @endif
          <div class="post-date" style="text-transform: capitalize;">
            {{ $novelty->created_at->formatLocalized('%d %B %Y %H:%M') }}
          </div>
          <div class="post-description">
            {{ $novelty->description }}
          </div>
          @if( $novelty->attachments && count($novelty->attachments) )
          <small>
            Archivos adjuntos
          </small>
          <div class="d-flex">
            @foreach( $novelty->attachments as $attachment )
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
    </div>
  </div>
  @else
  <div>
  <h1>Novedades</h1>
      <div class="table-responsive">
        @if( $novelties->count() )
        <table class="table">
          <thead>
            <th>Fecha</th>
            <th>Descripción</th>
            <th class="text-right">Opciones</th>
          </thead>
          <tbody>
            @foreach( $novelties as $novelty )
            <tr class="{{ $novelty->read ? 'read' : ''}}">
              <td>{{ $novelty->created_at }}</td>
              <td>{{ $novelty->excerpt }}</td>
              <td class="text-right">
                <div class="btn-group">
                  <a href="{{ route('novelties.show', ['novelty'=>$novelty->id]) }}" class="btn btn-sm btn-link">
                    <i class="material-icons">remove_red_eye</i>
                  </a>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @else
        <div class="alert alert-info">No hay Novedades para mostrar</div>
        @endif
      </div>
  </div>
  @endif
</div>
@endsection