@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-lg-6">
      <div class="card">
        <div class="card-body">
          <div class="form-section-title">
            CREAR PUBLICACIÓN
          </div>
          <form action="">
            <div class="mb-3">
              <input
                placeholder="Título"
                type="text"
                class="form-control"
                name="title"
                required>
            </div>
            <div class="mb-3">
              <textarea
                placeholder="Escribe aquí"
                rows="7"
                class="form-control"
                name="body"
                required></textarea>
            </div>
            <div class="mb-3">
              <input type="file" class="form-control" name="attachment" multiple>
            </div>
            <div class="d-flex justify-content-end">
              <button class="btn btn-primary" type="submit">
                Publicar
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
