@extends('layouts.app', ['title'=>'Residentes'])
@section('content')
  <div class="container">
    <residents :extension="{{ $extension }}" :residents="{{ $residents }}"/>
  </div>
@endsection