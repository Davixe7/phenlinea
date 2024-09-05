@extends('layouts.super')
@section('content')
<div class="container-fluid">
  <whatsappinstances :admins="{{ json_encode( $admins ) }}"></whatsappinstances>
</div>
@endsection