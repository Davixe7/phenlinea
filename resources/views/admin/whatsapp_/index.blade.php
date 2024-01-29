@extends('layouts.app', ['title'=>'Mensajería masiva'])

@section('styles')
<style>
  .list-group-item {
    border-left: 0;
    border-right: 0;
  }

  .list-group-item label {
    margin-bottom: 0;
  }

  .list-group-item:first-child {
    border-top: none;
  }

  .btn-round {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 1px solid #efefef;
    display: inline-flex;
    justify-content: center;
    align-items: center;
  }

  .btn-attachment i {
    color: #000000;
    transform: rotate(-90deg);
    transform-origin: center;
  }

  .attachmentDetails {
    font-family: 'monospace';
    font-size: .9em;
    text-transform: lowercase;
    color: #267bff;
  }

  .attachment-alert {
    display: inline-block;
    padding: 0 5px;
    background: #2f2f2f;
    color: #fff;
    font-size: .8em;
    border-radius: 3px;
  }

  .lightbulb {
    border: 3px solid #9ffb9f;
    border-radius: 50%;
    height: 15px;
    width: 15px;
    margin-right: 3px;
    background: green;
    display: inline-block;
  }

  .monospace {
    font-family: Monospace;
  }

  #qrPreloader {
    display: flex;
    height: 250px;
    justify-content: center;
    align-items: center;
  }

  .preloader {
    height: 30px;
    width: 30px;
    -webkit-animation: rotate 0.5s infinite linear;
    animation: rotate 0.5s infinite linear;
    margin-right: 10px;
    border: 3.5px solid #000;
    border-radius: 50%;
    border-top: 3.5px solid #fff;
  }

  #qrPreloader {
    display: flex;
    height: 250px;
    justify-content: center;
    align-items: center;
  }

  .preloader {
    height: 30px;
    width: 30px;
    -webkit-animation: rotate 0.5s infinite linear;
    animation: rotate 0.5s infinite linear;
    margin-right: 10px;
    border: 3.5px solid #000;
    border-radius: 50%;
    border-top: 3.5px solid #fff;
  }

  #extensionsFilter {
    height: 50px;
    width: 100%;
    padding: 0 20px;
  }
</style>
@endsection

@section('content')
  @if( isset($extensions) )
  <Whatsapp
    :extensions="{{ json_encode($extensions) }}"    
    :history="{{ json_encode($history) }}"
    logout-route="{{ route('whatsapp.logout') }}"
    whatsapp-instance-id="{{ $whatsapp_instance_id }}">
  </Whatsapp>
  @endif
  @includeUnless( isset($extensions), 'admin.whatsapp.login')
@endsection