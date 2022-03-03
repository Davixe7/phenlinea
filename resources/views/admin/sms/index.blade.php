@extends('layouts.app')
@section('content')
  <div class="container">
      <h1>
          Envío masivo de sms
      </h1>
      <messages 
        :extensions="{{ json_encode( $extensions ) }}"
        :log="{{ json_encode( $log ) }}"
      >
      </messages>
  </div>
@endsection