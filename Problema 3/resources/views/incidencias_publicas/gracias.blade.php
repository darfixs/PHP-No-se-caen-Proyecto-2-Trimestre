@extends('layout.base')

@section('titulo', 'Incidencia registrada · Nosecaen')

@section('contenido')
    <div class="text-center py-4">
        <i class="fa-solid fa-circle-check text-success" style="font-size: 3rem;"></i>
        <h2 class="mt-3">¡Incidencia registrada!</h2>
        <p class="text-muted">
            Gracias por avisarnos. Nuestro equipo asignará un operario lo antes
            posible y se pondrá en contacto contigo si necesita más información.
        </p>

        <a href="{{ url('/login') }}" class="btn btn-primary mt-3">
            <i class="fa-solid fa-right-to-bracket"></i> Volver al login
        </a>
        <a href="{{ url('/incidencia') }}" class="btn btn-secondary mt-3">
            <i class="fa-solid fa-plus"></i> Registrar otra
        </a>
    </div>
@endsection
