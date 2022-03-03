@extends('layouts.app')
@section('content')
  <div class="container">
    <stores :stores="{{ json_encode( $stores ) }}"/>
  </div>
@endsection
