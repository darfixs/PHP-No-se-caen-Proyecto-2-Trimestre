@extends('layout.base')

@section('titulo', 'Eliminar empleado · Nosecaen')

@section('contenido')
    <h2 class="text-danger">
        <i class="fa-solid fa-triangle-exclamation"></i> Eliminar empleado
    </h2>

    <div class="alert alert-warning">
        ¿Seguro que quieres eliminar al empleado
        <strong>{{ $empleado->nombre }}</strong>
        (DNI: {{ $empleado->dni }})?
        Esta acción no se puede deshacer.
    </div>

    <form method="post" action="{{ url('/empleados/eliminar/'.$empleado->id) }}" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-danger">
            <i class="fa-solid fa-trash"></i> Sí, eliminar
        </button>
    </form>

    <a href="{{ url('/empleados') }}" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Cancelar
    </a>
@endsection
