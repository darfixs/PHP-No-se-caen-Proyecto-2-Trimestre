@extends('layout.base')

@section('titulo', 'Nuevo cliente · Nosecaen')

@section('contenido')
    <h2><i class="fa-solid fa-briefcase"></i> Nuevo cliente</h2>

    <form method="post" action="{{ url('/clientes/crear') }}">
        @include('clientes._form')
    </form>
@endsection
