@extends('layouts.app', ['title'=>'Actualizar extensión'])
@section('content')
  <div class="container">
    <create-extension :_extension="{{ $extension}}" :extension-id="{{ $extension_id }}"/>
  </div>
@endsection