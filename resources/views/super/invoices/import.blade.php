@extends('layouts.super')

@section('styles')
<style>
  .file-input {
    border: 2px solid green;
    height: 43px;
  }

  button {
    color: #fff !important;
    word-wrap: normal;
    white-space: nowrap;
  }

  label {
    font-size: .9em;
    font-weight: 500;
  }

  h6 {
    padding: 20px;
    margin: -12px -20px 20px -20px;
    border-bottom: 1px solid lightgray;
  }

  .list-group-item {
    padding-bottom: 30px;
  }
  .pill {
      display: inline-block;
      padding: .25rem .75rem;
      font-size: .8rem;
      font-weight: 400;
      background: lightgray;
      color: #242424;
      border: none;
  }
  .pill-pagado {
      background: lightgreen;
      color: green;
  }
  .fab {
      width: 60px;
      height: 60px;
      border: none;
      border-radius: 50%;
      outline: none;
      position: fixed;
      bottom: 30px;
      right: 30px;
      color: #fff;
      background: #3490dc;
  }
</style>
@endsection

@section('content')
<div class="container">

<Upload
    :rows="{{ json_encode( array_values($invoices->toArray()) ) }}"
    :month="{{ $month }}"
    :year="{{ $year }}"
    :months-name="{{ json_encode($monthsName) }}">
</Upload>

</div>
@endsection
