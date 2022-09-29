@extends('layouts.app')
@section('content')
<div class="container">
  <h1>
    Cartelera
  </h1>
  <ul class="row" style="list-style-type: none; padding: 0;">
    @foreach( $posts as $post )
    <li class="col-lg-4">
      <div class="card post-card">
        <div class="card-body">
          <div class="post-header">
            <h2>{{ $post->title }}</h2>
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