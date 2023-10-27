@extends('layouts.app')
@section('content')
<resident-invoice-batches :batch="{{ isset($batch) ? json_encode($batch) : json_encode([]) }}"> 
</resident-invoice-batches>
@endsection