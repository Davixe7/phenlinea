@extends('layouts.app')
@section('content')
<div class="container">
  <extension-reminders :reminders="{{ json_encode( $reminders ) }}" :isAdmin="false"/>
</div>
@endsection
