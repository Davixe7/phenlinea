@extends('layouts.app')
@section('content')
  <div class="container">
    <create-census :extension-id="{{ $extension_id }}"/>
  </div>
@endsection