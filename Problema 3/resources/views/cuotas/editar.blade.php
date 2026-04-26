@extends('layout.base')

@section('titulo', 'Editar cuota · Nosecaen')

@section('contenido')
    <h2><i class="fa-solid fa-euro-sign"></i> Editar cuota #{{ $cuota->id }}</h2>

    <form method="post" action="{{ url('/cuotas/editar/'.$cuota->id) }}">
        @include('cuotas._form')
    </form>
@endsection
