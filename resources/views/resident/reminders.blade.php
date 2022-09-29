@extends('layouts.app')
@section('content')
<div class="container">
  <h1>
    Notificaciones
  </h1>
  <ul class="row" style="list-style-type: none; padding: 0;">
    @foreach( $reminders as $reminder )
    <li class="col-lg-4">
      <div class="card post-card">
        <div class="card-body">
          <div class="post-header">
            <h2>{{ $reminder->title }}</h2>
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
@endsection