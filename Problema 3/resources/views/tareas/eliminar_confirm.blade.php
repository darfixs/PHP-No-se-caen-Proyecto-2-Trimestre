@extends('layout.base')

@section('titulo', 'Eliminar tarea · Nosecaen')

@section('contenido')
    <h2 class="text-danger">
        <i class="fa-solid fa-triangle-exclamation"></i> Eliminar tarea
    </h2>

    <div class="alert alert-warning">
        ¿Seguro que quieres eliminar la tarea <strong>#{{ $tarea->id }}</strong>
        del cliente <strong>{{ $tarea->cliente?->nombre }}</strong>?
        Esta acción no se puede deshacer.
    </div>

    <form method="post" action="{{ url('/tareas/eliminar/'.$tarea->id) }}" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-danger">
            <i class="fa-solid fa-trash"></i> Sí, eliminar
        </button>
    </form>

    <a href="{{ url('/tareas') }}" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Cancelar
    </a>
@endsection
