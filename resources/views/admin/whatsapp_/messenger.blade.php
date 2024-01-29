@extends('layouts.app')

@push('styles')
<style>
  .whatsapp-search-form {
    flex: 1 0 auto;
    height: 50px;
    width: 100%;
    margin-top: .5rem;
    padding: 0 .75rem;
    border-radius: 5px;
    border: none;
    box-shadow: none;
    background-color: #efefef;
  }
  .whatsapp-search-form:focus {
    outline: none;
  }
  .whatsapp-filters {
    display: flex;
    margin-left: auto;
  }
  .whatsapp-filters-item {
    font-size: .9rem;
    text-align: center;
    padding: .5rem;
  }
  .whatsapp-filters-item:last-child {
    border-right: none;
  }
  .whatsapp-list {
    list-style-type: none;
    padding: 0;
    flex: 1 1 auto;
    overflow: auto;
  }
  .whatsapp-list li label {
    display: flex;
    align-items: center;
    width: 100%;
    padding: .25rem 0;
    border-bottom: 1px solid rgba(0,0,0,.095);
  }
</style>

<style>
  .multistep-steps {
    display: flex;
    justify-content: space-around;
    list-style-type: none;
    padding: 0;
    margin-bottom: 0;
  }
  .multistep-steps li {
    display: flex;
    flex-flow: column;
    justify-content: center;
    align-items: center;
    font-size: .9rem;
  }
  .multistep-steps li .number {
    color: #fff;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    width: 22.5px;
    height: 22.5px;
    font-size: .8rem;
    border-radius: 50%;
    background-color: grey;
    margin-bottom: .25rem;
  }
  .multistep-steps li .title {
  }
</style>
@endpush

@section('content')
<div class="container">
  <div class="row">
    <div class="col-lg-6 mx-auto">
      <div class="card">
        <div class="card-header d-flex align-items-center">
          Mensajería masiva
          <div class="ms-auto">
            <button class="btn btn-primary">Historial</button>
            <button class="btn btn-primary">Enviar nuevo</button>
          </div>
        </div>

        <div class="card-body" style="height: calc(100vh - 170px); overflow: auto;">
          <div class="table-responsive" style="margin: -15px;">
            <table class="table">
              <thead>
                <th>Cant.</th>
                <th>Fecha</th>
                <th>Asunto</th>
                <th>Estado</th>
              </thead>
              <tbody>
                @foreach(range(1,10) as $item)
                <tr>
                  <td>129</td>
                  <td>{{ now()->format('Y/m/d H:i') }}</td>
                  <td>Mensaje masívo módulo activo</td>
                  <td>Enviado</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <div class="multistep d-none" style="height: 100%; display: flex; flex-flow: column nowrap;">
            <ul class="multistep-steps" style="flex: 0 0 auto;">
              <li>
                <div class="number">1</div>
                <div class="title">Redactar</div>
              </li>

              <li>
                <div class="number">2</div>
                <div class="title">Destinatarios</div>
              </li>

              <li>
                <div class="number">3</div>
                <div class="title">Confirmar</div>
              </li>

              <li>
                <div class="number">4</div>
                <div class="title">Scanear</div>
              </li>
            </ul>

            <div id="form-1" class="d-flex flex-column" style="flex: 1 1 auto; overflow: hidden;">
              <input class="whatsapp-search-form" placeholder="Buscar apartamento"/>

              <div class="d-flex" style="border-bottom: 1px solid rgba(0,0,0,.25);">
                <label for="#" class="mt-2 d-flex align-items-center">
                  <input type="checkbox" class="me-2" style="width: 20px; height: 20px;">
                  0000 Seleccionados
                </label>

                <div class="whatsapp-filters">
                  <div class="whatsapp-filters-item">
                    <div class="btn btn-sm btn-secondary">Todos</div>
                  </div>
                  <div class="whatsapp-filters-item">
                    <div class="btn btn-sm btn-secondary">Propietarios</div>
                  </div>
                  <div class="whatsapp-filters-item">
                    <div class="btn btn-sm btn-secondary">Residentes</div>
                  </div>
                </div>
              </div>

              <ul class="whatsapp-list" style="flex: 1 1 auto;">
                @foreach($extensions as $extension)
                  <li>
                    <label for="checkbox{{$extension->id}}">
                      <input type="checkbox" class="me-2" style="width: 20px; height: 20px;" id="checkbox{{$extension->id}}">
                      {{ $extension->name }}
                    </label>
                  </li>
                @endforeach
              </ul>

              <div class="d-flex justify-content-end mt-auto" style="flex: 1 0 min-content;">
                <button class="btn btn-primary">
                  Continuar
                </button>
              </div>
            </div>

            <div id="form-2" class="d-none mt-3 d-flex flex-column" style="flex: 1 1 100%;">
              <textarea
                name=""
                id=""
                class="p-3"
                style="flex: 1 1 auto; height: 100%; width: 100%; background: #efefef; outline: none; border: none; border-radius: 5px;">
              </textarea>

              <div class="d-flex aling-items-center pt-3 mt-auto">
                <label for="#">
                  <input type="file" placeholder="Seleccionar archivo">
                </label>
                <button class="btn btn-primary ms-auto">
                  Continuar
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection