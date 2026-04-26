@extends('layout.base')

@section('titulo', 'Detalle tarea · Nosecaen')

@section('contenido')
    <h2><i class="fa-solid fa-eye"></i> Detalle de tarea #{{ $tarea->id }}</h2>

    <div class="row">
        <div class="col-md-6">
            <h5 class="text-muted">Cliente</h5>
            <p>
                <strong>{{ $tarea->cliente?->nombre }}</strong>
                ({{ $tarea->cliente?->cif }})
            </p>

            <h5 class="text-muted">Contacto</h5>
            <p>
                {{ $tarea->personaNombre }}<br>
                <i class="fa-solid fa-phone"></i> {{ $tarea->telefono }}<br>
                <i class="fa-solid fa-envelope"></i> {{ $tarea->correo }}
            </p>

            <h5 class="text-muted">Descripción</h5>
            <p>{{ $tarea->descripcionTarea }}</p>

            @if($tarea->anotacionesAnteriores)
                <h5 class="text-muted">Anotaciones anteriores</h5>
                <p>{{ $tarea->anotacionesAnteriores }}</p>
            @endif

            @if($tarea->anotacionesPosteriores)
                <h5 class="text-muted">Anotaciones posteriores</h5>
                <p>{{ $tarea->anotacionesPosteriores }}</p>
            @endif
        </div>

        <div class="col-md-6">
            <h5 class="text-muted">Ubicación</h5>
            <p>
                {{ $tarea->direccionTarea }}<br>
                @if($tarea->codigoPostal) {{ $tarea->codigoPostal }} @endif
                {{ $tarea->poblacion }}<br>
                @if($tarea->provinciaRel) {{ $tarea->provinciaRel->nombre }} @endif
            </p>

            <h5 class="text-muted">Operario asignado</h5>
            <p>
                @if($tarea->operario)
                    {{ $tarea->operario->nombre }}
                @else
                    <span class="text-danger">Sin asignar</span>
                @endif
            </p>

            <h5 class="text-muted">Estado</h5>
            <p>
                @php
                    $labelEstado = match($tarea->estadoTarea) {
                        'P' => 'Pendiente',
                        'R' => 'Realizada',
                        'C' => 'Cancelada',
                        default => $tarea->estadoTarea,
                    };
                @endphp
                <span class="badge bg-primary">{{ $labelEstado }}</span>
            </p>

            <h5 class="text-muted">Fecha creación</h5>
            <p>{{ $tarea->fechaCreacion?->format('d/m/Y H:i') }}</p>

            <h5 class="text-muted">Fecha realización</h5>
            <p>{{ $tarea->fechaRealizacion?->format('d/m/Y') }}</p>

            @if($tarea->ficheroResumen)
                <h5 class="text-muted">Fichero resumen</h5>
                <p>
                    <a href="{{ url('/tareas/adjunto/'.$tarea->id) }}"
                       class="btn btn-sm btn-outline-primary">
                        <i class="fa-solid fa-download"></i>
                        {{ $tarea->ficheroNombreOriginal ?? 'Descargar' }}
                    </a>
                </p>
            @endif
        </div>
    </div>

    <hr>

    <a href="{{ url('/tareas') }}" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Volver
    </a>

    @if(auth()->user()->esAdmin())
        <a href="{{ url('/tareas/editar/'.$tarea->id) }}" class="btn btn-warning">
            <i class="fa-solid fa-pen"></i> Modificar
        </a>
    @elseif(auth()->user()->esOperario() && $tarea->estadoTarea === 'P')
        <a href="{{ url('/tareas/completar/'.$tarea->id) }}" class="btn btn-success">
            <i class="fa-solid fa-check"></i> Completar
        </a>
    @endif
@endsection
