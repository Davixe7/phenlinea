@extends('layouts.jquery_app', ['title'=>'Registros de ingreso'])

@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
<style>
  .dataTables_length, .dataTables_filter {
    padding: .5rem 1rem;
  }
</style>
@endsection

@section('content')
<div class="container">
  <ul class="nav nav-pills nav-justified">
    <li class="nav-item">
      <a class="nav-link text-center" href="{{ route('visits.index') }}">Porteria</a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-center active" href="{{ route('visits.accesslogs') }}">Registros de apertura</a>
    </li>
  </ul>
  @if( auth()->user()->device_serial_number )
  <div class="table-responsive">
    <table id="example" class="display" style="width:100%">
      <thead>
        <tr>
          <th>Punto de acceso</th>
          <th>Residente</th>
          <th>Foto</th>
          <th>Fecha</th>
        </tr>
      </thead>
    </table>
  </div>
  @else
  Esta unidad no esta asociada a una comunidad Zhyaf
  @endif
</div>
@endsection

@if( auth()->user()->device_serial_number )
@section('scripts')
<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
<script>
  $(document).ready(function() {
    $('#example').DataTable({
      ajax: {
        url: '/devices/accessLogs', // Reemplaza esto con la URL real de tu API
        dataSrc: 'list'
      },
      columns: [{
          data: 'doorName'
        },
        {
          data: 'empName'
        },
        {
          data: 'captureImage',
          render: function(data, type, row) {
            return '<img src="' + data + '" alt="Foto" height="50">';
          }
        },
        {
          data: 'eventTime',
          render: function(data, type, row) {
            return new Date(data).toLocaleString('es-ES', {
              year: 'numeric',
              month: '2-digit',
              day: '2-digit',
              hour: '2-digit',
              minute: '2-digit',
              second: '2-digit'
            });
          }
        }
      ],
      serverSide: true,
      processing: true,
      pageLength: 1000,
      lengthMenu: [10, 25, 50, 100, 1000],
      pagingType: 'full_numbers',
      language: {
        url: '//cdn.datatables.net/plug-ins/2.1.8/i18n/es-ES.json'
      }
    });
  });
</script>
@endsection
@endif