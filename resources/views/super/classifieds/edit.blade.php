@extends('layouts.app')
@section('content')
<div class="container">
  <h1 class="mb-3">Editar clasificado</h1>
  <create-ad :locations="{{json_encode( $states )}}" :ad="{{ json_encode($ad) }}"/>
</div>
@endsection