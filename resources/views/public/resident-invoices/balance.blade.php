@extends('layouts.public', ['title'=>'Facturación de residentes'])
@section('content')
<div class="container" style="max-width: 875px;">
<x-extensions.balance
  :extension="$extension"
  :startdate="$start_date"
  :enddate="$end_date"
  :invoices="$invoices"
  :total="$total"
  >
</x-extensions.balance>
</div>
@endsection