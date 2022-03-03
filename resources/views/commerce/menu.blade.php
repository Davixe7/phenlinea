@extends('layouts.app')
@section('content')
<div class="container">
  <products :menu="{{ json_encode($menu) }}">
</div>
@endsection