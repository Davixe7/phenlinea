@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row" style="height: calc(100vh - 100px); display: flex; align-items: center; justify-content: center;">
    <div class="col-md-6 mx-auto">
      <h1>
        Módulo Desactivado por la Administración
      </h1>
      <p>
        Para más información, pongase en contacto
      </p>
      <a
        href="https://api.whatsapp.com/send?phone=573144379170&text=Hola,%20necesito%20asistencia%20relativa%20a%20PHEnLinea"
        target="_blank"
        class="btn btn-success text-white">
        WhatsApp
      </a>
    </div><!-- end col-md-6 -->
  </div><!-- end row -->
</div>
@endsection