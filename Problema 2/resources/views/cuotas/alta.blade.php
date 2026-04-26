@extends('layout.base')

@section('titulo', 'Nueva cuota · Nosecaen')

@section('contenido')
    <h2><i class="fa-solid fa-euro-sign"></i> Nueva cuota</h2>
    <p class="text-muted small">
        Las cuotas excepcionales se anotan desde aquí. Para generar la cuota
        mensual a todos los clientes a la vez, usa el botón
        <em>"Generar remesa mensual"</em> del listado.
    </p>

    <form method="post" action="{{ url('/cuotas/crear') }}">
        @include('cuotas._form')
    </form>
@endsection
