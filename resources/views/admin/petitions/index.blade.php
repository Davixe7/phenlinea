@extends('layouts.app')
@section('content')
<div class="container">
    <div class="table-responsive">
        <h1>
            PQRS
        </h1>
        <table class="table">
            <thead>
                <th>
                    Fecha
                </th>
                <th>
                    Nombres
                </th>
                <th>
                    Teléfono
                </th>
                <th>
                    Estado
                </th>
            </thead>
            <tbody>
                @foreach($pqrss as $pqrs)
                    <tr>
                        <td>
                            <div class="{{ $pqrs->status }}">
                                {{ $pqrs->created_at->format('Y-m-d H:i:s') }}
                            </div>
                        </td>
                        <td>
                            <div class="{{ $pqrs->status }}">
                                {{ $pqrs->name }}
                            </div>
                        </td>
                        <td>
                            <div class="{{ $pqrs->status }}">
                                {{ $pqrs->phone }}
                            </div>
                        </td>
                        <td>
                            <div class="{{ $pqrs->status }}">
                              @if( $pqrs->status == 'approved' )
                              <div class="text-success">
                                  Estado: Aprobado
                              </div>
                              @elseif( $pqrs->status == 'pending' )
                              <div class="text-info">
                                  Estado: Pendiente
                              </div>
                              @else
                              <div class="text-danger">
                                  Estado: Rechazado
                              </div>
                              @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection