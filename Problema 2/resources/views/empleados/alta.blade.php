@extends('layout.base')

@section('titulo', 'Nuevo empleado · Nosecaen')

@section('contenido')
    <h2><i class="fa-solid fa-user-plus"></i> Nuevo empleado</h2>

    <form method="post" action="{{ url('/empleados/crear') }}">
        @include('empleados._form', ['esEdicion' => false])
    </form>
@endsection
