@extends('layout.base')

@section('titulo', 'Editar empleado · Nosecaen')

@section('contenido')
    <h2><i class="fa-solid fa-user-pen"></i> Editar empleado</h2>

    <form method="post" action="{{ url('/empleados/editar/'.$empleado->id) }}">
        @include('empleados._form', ['esEdicion' => true])
    </form>
@endsection
