@extends('layouts.app')
@section('content')
<invoice-batch-form
  :batch="{{ $batch }}"> 
</invoice-batch-form>
@endsection