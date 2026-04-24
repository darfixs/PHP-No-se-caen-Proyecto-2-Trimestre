@extends('layout.base')

@section('titulo', 'Empleados · Nosecaen')

@section('contenido')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0"><i class="fa-solid fa-users"></i> Listado de empleados</h2>
        <a href="{{ url('/empleados/crear') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Nuevo empleado
        </a>
    </div>

    @if($empleados->isEmpty())
        <div class="alert alert-info">No hay empleados todavía.</div>
    @else
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-dark">
            <tr>
                <th>DNI</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Tipo</th>
                <th>Fecha alta</th>
                <th style="width: 180px;">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach($empleados as $e)
                <tr>
                    <td>{{ $e->dni }}</td>
                    <td>{{ $e->nombre }}</td>
                    <td>{{ $e->correo }}</td>
                    <td>{{ $e->telefono }}</td>
                    <td>
                        <span class="badge bg-{{ $e->esAdmin() ? 'danger' : 'secondary' }}">
                            {{ $e->tipo }}
                        </span>
                    </td>
                    <td>{{ $e->fecha_alta?->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ url('/empleados/editar/'.$e->id) }}"
                           class="btn btn-sm btn-warning">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <a href="{{ url('/empleados/eliminar/'.$e->id) }}"
                           class="btn btn-sm btn-danger">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{-- Paginación Laravel --}}
        {{ $empleados->links() }}
    @endif
@endsection
