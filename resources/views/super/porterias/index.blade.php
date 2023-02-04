@extends('layouts.super')
@section('content')
<div class="container">
    <porterias
        :rows="{{ json_encode( $porterias ) }}"
        :admins="{{ json_encode( $admins ) }}">
    </porterias>
</div>
@endsection
