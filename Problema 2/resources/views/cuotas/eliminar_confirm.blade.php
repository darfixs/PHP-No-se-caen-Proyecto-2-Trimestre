@extends('layout.base')

@section('titulo', 'Eliminar cuota · Nosecaen')

@section('contenido')
    <h2 class="text-danger">
        <i class="fa-solid fa-triangle-exclamation"></i> Eliminar cuota
    </h2>

    <div class="alert alert-warning">
        ¿Seguro que quieres eliminar la cuota
        <strong>#{{ $cuota->id }}</strong>
        del cliente <strong>{{ $cuota->cliente?->nombre }}</strong>
        con importe
        <strong>{{ number_format($cuota->importe, 2, ',', '.') }} {{ $cuota->moneda }}</strong>?
    </div>

    <form method="post" action="{{ url('/cuotas/eliminar/'.$cuota->id) }}" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-danger">
            <i class="fa-solid fa-trash"></i> Sí, eliminar
        </button>
    </form>

    <a href="{{ url('/cuotas') }}" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Cancelar
    </a>
@endsection
