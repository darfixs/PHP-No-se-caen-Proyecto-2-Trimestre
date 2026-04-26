@extends('layout.base')

@section('titulo', 'Nueva tarea · Nosecaen')

@section('contenido')
    <h2><i class="fa-solid fa-clipboard-list"></i> Nueva tarea</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            Hay errores en el formulario. Revisa los campos marcados en rojo.
        </div>
    @endif

    <form method="post" action="{{ url('/tareas/crear') }}">
        @include('tareas._form')
    </form>
@endsection
