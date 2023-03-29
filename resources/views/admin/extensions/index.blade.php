@extends('layouts.app', ['title'=>'Citofonía y Censo'])
@section('content')
  <div class="container">
    <extensions :items="{{ json_encode( $extensions ) }}"/>
  </div>
@endsection