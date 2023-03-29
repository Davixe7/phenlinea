@extends('layouts.app', ['title'=>'Comunidad QR'])

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

  .table-responsive tbody td {
    font-size: .9em;
    font-weight: 400;
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
<Whatsapp :history="{{ json_encode($history) }}" :mode="'comunity'">
  <template v-slot:banner>
    <div class="card text-white bg-primary mb-3">
      <div class="card-body" style="font-size: 17px; letter-spacing: .007em; line-height: 1.5em; color: #fff;">
        Comunidad QR
      </div>
      <div class="card-body pt-0" style="font-size: 15px; line-height: 1.5em; color: rgba(255,255,255,.87);">
        Los mensajes que envíe a traves de este panel seran recibidos por los miembros del grupo de WhatsApp de su comunidad QR
      </div>
      <div class="card-actions d-flex justify-content-center">
        <a href="#" class="btn btn-flat text-white">
          <i class="material-symbols-outlined">link</i>
          <span>
            Copiar link
          </span>
        </a>
        <a href="#" class="btn btn-flat text-white">
          <i class="material-symbols-outlined">qr_code</i>
          <span>
            Descargar QR
          </span>
        </a>
      </div>
    </div>
  </template>
</Whatsapp>
@endsection