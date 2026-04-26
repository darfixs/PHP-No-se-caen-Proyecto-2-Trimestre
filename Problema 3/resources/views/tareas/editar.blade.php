@extends('layout.base')

@section('titulo', 'Modificar tarea · Nosecaen')

@section('contenido')
    <h2><i class="fa-solid fa-pen"></i> Modificar tarea #{{ $tarea->id }}</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            Hay errores en el formulario. Revisa los campos marcados en rojo.
        </div>
    @endif

    <form method="post" action="{{ url('/tareas/editar/'.$tarea->id) }}">
        @include('tareas._form')
    </form>
@endsection
