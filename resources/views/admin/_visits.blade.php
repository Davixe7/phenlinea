@extends('layouts.app')
@section('content')
<div class="container py-4 mt-4">
    <admin-visits :visits="{{ json_encode( $visits ) }}"/>
</div>
@endsection