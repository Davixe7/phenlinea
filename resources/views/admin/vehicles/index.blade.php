@extends('layouts.app', ['title'=>'Vehículos'])
@section('content')
  <div class="container">
    <vehicles :extension="{{ $extension}}" :vehicles="{{ $vehicles }}"/>
  </div>
@endsection