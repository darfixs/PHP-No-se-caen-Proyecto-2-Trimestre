@extends('layout.base')

@section('titulo', 'Eliminar cliente · Nosecaen')

@section('contenido')
    <h2 class="text-danger">
        <i class="fa-solid fa-triangle-exclamation"></i> Eliminar cliente
    </h2>

    <div class="alert alert-warning">
        ¿Seguro que quieres eliminar al cliente
        <strong>{{ $cliente->nombre }}</strong>
        (CIF: {{ $cliente->cif }})?
        Se borrarán también todas sus cuotas asociadas.
        Esta acción no se puede deshacer.
    </div>

    <form method="post" action="{{ url('/clientes/eliminar/'.$cliente->id) }}" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-danger">
            <i class="fa-solid fa-trash"></i> Sí, eliminar
        </button>
    </form>

    <a href="{{ url('/clientes') }}" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Cancelar
    </a>
@endsection
