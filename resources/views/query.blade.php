<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
  <title>Buscar residentes</title>
</head>
<body>
  <div class="container-fluid">
    <form action="{{ route('query-residents') }}">
      <div>
        <label for="#">Nombre del residente</label>
        <input type="text" class="form-control" name="device_resident_id">
      </div>
      <button class="btn btn-primary">
        Buscar
      </button>
    </form>

    @if( $residents && count( $residents ) )
    <table class="table">
      <thead>
        <th></th>
        <th>ID</th>
        <th>Zhyaf ID</th>
        <th>Apto</th>
        <th>Nombre</th>
        <th></th>
      </thead>
      <tbody>
        @foreach( $residents as $resident )
        <tr>
          <td>
            @if( $resident->picture )
              <img src="{{ $resident->picture }}" alt="" style="width: 40px; height: 40px; border-radius: 50%;">
            @else
              <div></div>
            @endif
          </td>
          <td>{{ $resident->id }}</td>
          <td>{{ $resident->device_resident_id }}</td>
          <td>
            <a
              class="btn btn-link"
              target="_blank"
              href="{{ route('extensions.residents.index', $resident->extension_id) }}">
              {{ $resident->extension->name }}
            </a>
          </td>
          <td>{{ $resident->name }}</td>
          <td>
            <form action="{{ route('residentes.eliminar', $resident->id) }}" method="POST">
              @csrf
              @method('DELETE')
              <button class="btn btn-danger">
                <i class="material-symbols-outlined">delete</i>
              </button>
            </form>

            <form action="{{ route('update-resident-zhyafid') }}" method="POST">
              @csrf
              <input type="number" class="form-control" name="device_resident_id">
              <input type="hidden" class="form-control" name="resident_id" value="{{ $resident->id }}">
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    @else
      <div class="alert alert-info">
        No hay resultados coincidentes con tu busqueda
      </div>
    @endif
  </div>
</body>
</html>