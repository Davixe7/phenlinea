@extends('layouts.app')
@section('content')
<div class="container pt-4">
  <div class="row">
    <div class="col-lg-4">
      <div class="table-responsive text-center">
        <h1>
          WhatsApp - Mensajería general
        </h1>
        <div id="instance_id" class="text-secondary d-none" style="padding: 0 1.15rem;">
          {{ $instance_id }}
        </div>
        @if( isset( $base64 ) && $base64 )
        <img src="{{ $base64 }}" id="qrImage" class="mx-auto">
        <div id="qrPreloader" style="display: none;">
          <div class="preloader"></div>
        </div>
        @else
        <img src="#" id="qrImage" style="display: none;" class="mx-auto">
        <div id="qrPreloader">
          <div class="preloader"></div>
        </div>
        @endif

        <div class="text-center">
          <div class="px-4">
            Si el código QR expira, utilize el botón
          </div>
          <div class="d-flex justify-content-center">
            <a href="{{route('whatsapp.index')}}" class="btn btn-outline-success">Actualizar</a>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-7">
      <ul class="list-group m-t-25">
        <li class="list-group-item active bg-info text-uppercase"><i class="far fa-question-circle"></i> Para comenzar a usar la herramienta, debe conectar su número de teléfono.</li>
        <li class="list-group-item">Paso 1: Abre WhatsApp en tu teléfono</li>
        <li class="list-group-item">Paso 2: toca Menú o Configuración y selecciona WhatsApp Web</li>
        <li class="list-group-item">Paso 3: apunta tu teléfono a esta pantalla y captura el código de arriba</li>
        <li class="list-group-item text-danger">
          <video width="100%" height="320" autoplay="" muted="" loop="">
            <source src="https://asistbot.com/inc/public/whatsapp_profiles/assets/img/scan.mp4" type="video/mp4">
          </video>
        </li>
      </ul>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>

  const instance_id = document.querySelector('#instance_id').innerHTML
  const qrImage     = document.querySelector('#qrImage')
  const qrPreloader = document.querySelector('#qrPreloader')

  function setQrDisplay(src = null){
    qrImage.style.display     = src ? 'block' : 'none'
    qrPreloader.style.display = src ? 'none'  : 'flex'
    qrImage.setAttribute('src', src ? src : qrImage.src)
    return
  }

  async function getQR(){
    let src = (await axios.get(`/whatsapp/getQR/?instance_id=${instance_id}`)).data.data
    setQrDisplay(src)
  }

  setInterval(() => getQR(), 31000)

  setInterval(function() {
    axios.get('/whatsapp/status')
    .then(response => response.data.data ? location.href = '/whatsapp' : '')
  }, 5000)
</script>
@endsection
