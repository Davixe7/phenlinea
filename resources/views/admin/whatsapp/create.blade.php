@extends('layouts.app', ['title'=>'Mensajería masiva'])
@section('content')
<Whatsapp
  :extensions="{{ json_encode($extensions) }}"

  @if( $message )
    :message="{{ json_encode($message) }}"
  @endif>
</Whatsapp>
@endsection

@push('scripts')
<script src="https://cdn.socket.io/4.7.4/socket.io.min.js"></script>
@endpush