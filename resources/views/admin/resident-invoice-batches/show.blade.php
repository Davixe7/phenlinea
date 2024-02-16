@extends('layouts.app', ['title'=>'Facturación de residentes'])
@section('content')
<resident-invoice-batch :batch="{{ json_encode($resident_invoice_batch) }}"/>
@endsection