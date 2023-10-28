@extends('layouts.app')
@section('content')
<extension-resident-invoices
  :resident_invoices="{{ json_encode($resident_invoices) }}"
  :extension="{{ json_encode($extension) }}">
</extension-resident-invoices>
@endsection