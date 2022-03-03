@extends('layouts.app')
@section('content')
<div class="container">
  <ads-table :locations="{{json_encode( $states )}}" :ads="{{ json_encode( $ads ) }}"/>
</div>
@endsection