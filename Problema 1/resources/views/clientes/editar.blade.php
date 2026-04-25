@extends('layout.base')

@section('titulo', 'Editar cliente · Nosecaen')

@section('contenido')
    <h2><i class="fa-solid fa-briefcase"></i> Editar cliente</h2>

    <form method="post" action="{{ url('/clientes/editar/'.$cliente->id) }}">
        @include('clientes._form')
    </form>
@endsection
