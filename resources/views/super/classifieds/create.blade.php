@extends('layouts.super')
@section('content')
<div class="container">
  <h1 class="mb-3">Registrar clasificado</h1>
  <create-ad :locations="{{json_encode( $states )}}"/>
</div>
@endsection