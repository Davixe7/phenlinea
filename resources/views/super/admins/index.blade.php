@extends('layouts.super')
@section('content')
<div class="container-fluid">
  <admins :admins="{{ json_encode( $admins ) }}"></admins>
</div>
@endsection
