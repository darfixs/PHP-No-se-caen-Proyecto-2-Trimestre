@extends('layout.base')

@section('titulo', 'Clientes · Nosecaen')

@section('contenido')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0"><i class="fa-solid fa-briefcase"></i> Listado de clientes</h2>
        <a href="{{ url('/clientes/crear') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Nuevo cliente
        </a>
    </div>

    @if($clientes->isEmpty())
        <div class="alert alert-info">No hay clientes todavía.</div>
    @else
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-dark">
            <tr>
                <th>CIF</th>
                <th>Nombre</th>
                <th>País</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Cuota</th>
                <th style="width: 140px;">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach($clientes as $c)
                <tr>
                    <td>{{ $c->cif }}</td>
                    <td>{{ $c->nombre }}</td>
                    <td>{{ $c->pais?->nombre }}</td>
                    <td>{{ $c->telefono }}</td>
                    <td>{{ $c->correo }}</td>
                    <td>{{ number_format($c->importe_cuota, 2, ',', '.') }} {{ $c->moneda }}</td>
                    <td>
                        <a href="{{ url('/clientes/editar/'.$c->id) }}"
                           class="btn btn-sm btn-warning">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <a href="{{ url('/clientes/eliminar/'.$c->id) }}"
                           class="btn btn-sm btn-danger">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $clientes->links() }}
    @endif
@endsection
