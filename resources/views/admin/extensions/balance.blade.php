@extends('layouts.app')
@section('styles')
<style>
  .w-fit {
    width: fit-content !important;
  }
</style>
@endsection
@section('content')
<div class="container">
  <ul class="nav nav-pills nav-fill mb-3">
    <li class="nav-item">
      <a class="nav-link" href="{{ route('extensions.edit', $extension) }}">
        Apartamento
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('extensions.residents.index', $extension) }}">
        Residentes
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('extensions.vehicles.index', $extension) }}">
        Vehículos
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link active" href="{{ route('extensions.balance', $extension) }}`">
        Facturación
      </a>
    </li>
  </ul>
  <x-extensions.balance :extension="$extension" :startdate="$start_date" :enddate="$end_date" :invoices="$invoices" :total="$total">
  </x-extensions.balance>
</div>
@endsection