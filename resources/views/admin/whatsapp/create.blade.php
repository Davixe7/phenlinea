@extends('layouts.app', ['title'=>'Mensajería masiva'])
@section('content')
<Whatsapp
  :extensions="{{ json_encode($extensions) }}"

  @if($instance_id)
    :instance_id="'{{ $instance_id }}'"
  @endif

  @if( $message )
    :message="{{ json_encode($message) }}"
  @endif

  :phone="{{ $phone }}"
  :method="'{{ $method }}'">
</Whatsapp>
@endsection

@push('scripts')
<script src="https://cdn.socket.io/4.7.4/socket.io.min.js"></script>
@endpush