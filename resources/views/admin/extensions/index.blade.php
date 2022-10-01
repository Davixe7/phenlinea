@extends('layouts.app')
@section('content')
  <div class="container">
    <extensions :items="{{ json_encode( $extensions ) }}"/>
  </div>
@endsection