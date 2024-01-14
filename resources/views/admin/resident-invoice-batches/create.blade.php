@extends('layouts.app')
@section('content')
<invoice-uploader
  :batch="{{ isset($batch) ? json_encode($batch) : json_encode([]) }}"> 
</invoice-uploader>
@endsection