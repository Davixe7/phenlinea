@extends('layouts.super')
@section('content')
<div class="container">
  <Users :users="{{ json_encode($users) }}"></Users>
</div>
@endsection