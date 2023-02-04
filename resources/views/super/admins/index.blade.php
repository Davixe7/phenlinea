@extends('layouts.super')
@section('content')
<div class="container">
  <admins :admins="{{ json_encode( $admins ) }}"></admins>
</div>
@endsection
