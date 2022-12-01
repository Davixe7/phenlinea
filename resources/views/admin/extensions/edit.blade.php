@extends('layouts.app')
@section('content')
  <div class="container">
    <create-extension :extension-id="{{ $extension_id }}"/>
  </div>
@endsection