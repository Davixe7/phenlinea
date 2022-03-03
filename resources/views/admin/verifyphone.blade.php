@extends('layouts.app')
@section('styles')
<style>
  label, .card-header {
    font-weight: 500;
  }
  .card-header {
    padding: 15px 20px !important;
  }
  #verification_code {
    font-size: 20px;
    text-align: center;
    letter-spacing: 3px;
    height: 50px;
  }
</style>
@endsection
@section('content')
<div class="container">
  <div class="row" style="height: calc(100vh - 100px); display: flex; justify-content: center;">
    <div class="col-lg-6 mx-auto" style="margin-top: 100px; max-width: 550px;">
      <div class="card card-outlined" style="box-shadow: none; border: 1px solid rgba(0,0,0,.25); border-radius: 3px;">
        <div class="card-header" style="font-size: 16px; border-bottom: none;">
          Verificar Número de Teléfono
        </div>
        <div style="padding: 0 20px;">
          <hr>
        </div>
        <div class="card-body">
          <div class="alert alert-info" style="font-size: 14px; padding: 15px 20px;">
            <i class="material-icons mr-2">
              lightbulb
            </i>
            Código de confirmación enviado via SMS al número asociado
          </div>
          <div class="form-group" style="margin-bottom: 30px;">
            <label for="verification_code" class="mb-2" style="font-size: 14px;">
              Código de Verificación
            </label>
            <input
              minlength="6"
              maxlength="6"
              max="6"
              type="text"
              id="verification_code"
              class="form-control"
              name="verification_code"
              required>
          </div>

          <div class="form-group d-flex">
            <v-btn class="mr-3" style="flex: 1 0 auto;">
              Reenviar Código
            </v-btn>
            <v-btn dark style="flex: 1 0 auto;">
              Confirmar
            </v-btn>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
@endsection