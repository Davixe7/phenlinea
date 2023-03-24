@extends('layouts.super')

@section('styles')
<style>
    .table-responsive {
      max-height: calc(100vh - 100px);
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
  <admins :admins="{{ json_encode( $admins ) }}"></admins>
</div>
@endsection
