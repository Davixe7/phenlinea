@extends('layouts.app')
@section('content')
<div class="container" style="box-shadow: none;">
  <sms-log :log="{{ json_encode( $log ) }}"/>
</div>
@endsection