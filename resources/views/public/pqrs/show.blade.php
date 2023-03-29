@extends('layouts.legacy')
@section('styles')
<style>
  .table-responsive h1 {
    padding: 30px;
  }

  .card-body {
    padding: 0 30px;
  }

  .form-group {
    margin-bottom: 35px;
  }

  .material-form .form-group label {
    display: block;
    font-size: 14px;
    margin-bottom: 10px;
  }

  .material-form .form-group .form-control {
    font-size: 16px;
    padding: 0;
    border: none;
    border-bottom: 1px solid #000;
    border-radius: 0;
    background: none;
  }

  .material-form .form-group .form-control:focus {
    border-bottom: 1px solid var(--primary);
    box-shadow: none;
  }

  .navbar.navbar-light {
    background: #4B7094;
    color: #fff;
  }

  .navbar.navbar-light a {
    color: #fff !important;
  }

  .form-check-label {
    display: flex;
    align-items: center;
    margin-right: 1rem;
  }

  .form-check-label input.form-control {
    width: 17px;
    height: 17px;
    margin-right: .35rem;
  }

  .badge {
    padding: 3px 10px;
    border-radius: 5px;
    font-weight: 500;
    font-size: .85rem !important;
    height: fit-content;
  }

  .media-wrapper img {
    padding: .25em;
    border: 1px solid cadetblue;
    cursor: pointer;
  }
</style>
@endsection
@section('content')
<div class="container" style="max-width: 480px;">
  <div class="table-responsive mt-5 pb-1">
    <div class="d-flex align-items-center justify-space-between">
      <h1>
        Detalle del PQRS
      </h1>

      @if( $pqrs->status == 'pending' )
      <div class="bg-secondary text-white badge">
        Pendiente
      </div>
      @elseif( $pqrs->status == 'read' )
      <div class="bg-info text-white badge">
        Leido
      </div>
      @else
      <div class="bg-success text-white badge">
        Respuesta enviada
      </div>
      @endif

      <div class="px-5">
        <b>
          {{ str_pad( $pqrs->id, 4, '0', STR_PAD_LEFT ) }}
        </b>
      </div>
    </div>
    <div class="card-body material-form">
      @if( auth()->check('admin') )
      <div class="form-group">
        <label>Unidad Residencial</label>
        <input class="form-control" type="text" value="{{ $pqrs->admin->name }}" disabled>
      </div>
      <div class="form-group">
        <label>Nro Apartamento. (opcional)</label>
        <input class="form-control" type="text" name="apto" value="{{ $pqrs->apto }}">
      </div>
      <div class="form-group">
        <label>Nombre y apellidos</label>
        <input class="form-control" type="text" name="name" value="{{ $pqrs->name }}">
      </div>
      <div class="row">
        <div class="form-group col-lg-6">
          <label>Móvil (A este móvil se envía el seguimiento del PQRS)</label>
          <input class="form-control" type="tel" name="phone" value="{{ $pqrs->phone }}">
        </div>
        <div class="form-group col-lg-6">
          <label>Teléfono 2 (opcional)</label>
          <input class="form-control" type="tel" name="phone_2" value="{{ $pqrs->phone_2 }}">
        </div>
      </div>
      <div class="form-group">
        <label>Descripción</label>
        <textarea class="form-control" name="description" required>{{ $pqrs->description }}</textarea>
      </div>

      @if( $attachments && count( $attachments ) )
      <div class="form-group row">
        @foreach( $pqrs->getMedia('attachments') as $media)
        <div class="col-4 col-lg-3 media-wrapper" @click="showImg( {{ $loop->index }} )">
          <img src="{{ $media->original_url }}" style="width: 100%;">
        </div>
        @endforeach
      </div>
      @endif

      @elseif( $pqrs->answer )
      <div class="form-group">
        <label>Respuesta</label>
        <textarea class="form-control" name="description" required>{{ $pqrs->answer }}</textarea>
      </div>
      @endif

      @if( auth()->check('admin') )
      <form action="{{ route('petitions.update', ['petition'=>$pqrs->id]) }}" method="POST">
        <div class="d-flex">
          @csrf
          @method('PUT')
        </div>
        <div class="form-group">
          <label>Respuesta</label>
          <textarea class="form-control" name="answer">
          {{ $pqrs->answer }}
          </textarea>
        </div>
        <div class="text-right">
          <button class="btn btn-secondary text-white" type="submit">
            Actualizar
          </button>
        </div>
      </form>
      @endif
    </div>
  </div>
</div>

<vue-easy-lightbox :visible="visibleRef" :imgs="imgs" :index="indexRef" @hide="onHide">
</vue-easy-lightbox>
@endsection

@section('scripts')
<script src="https://unpkg.com/vue@next"></script>
<script src="https://unpkg.com/vue-easy-lightbox@next/dist/vue-easy-lightbox.umd.min.js"></script>
<script>
  const app = Vue.createApp({
    setup() {
      const visibleRef = Vue.ref(false)
      const indexRef = Vue.ref(0)
      const imgs = @json($attachments)
      const showImg = (index) => {
        indexRef.value = index
        visibleRef.value = true
      }
      const onHide = () => visibleRef.value = false
      return {
        visibleRef,
        indexRef,
        imgs,
        showImg,
        onHide
      }
    }
  })
  app.use(VueEasyLightbox) // global variable
  app.mount('#app')
</script>
@endsection