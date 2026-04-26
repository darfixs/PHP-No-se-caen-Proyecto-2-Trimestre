@extends('layout.base')

@section('titulo', 'Tareas · Nosecaen')

@section('contenido')
    @php $rol = auth()->user()->tipo; @endphp

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h2 class="mb-0">
            <i class="fa-solid fa-clipboard-list"></i>
            @if($rol === 'Administrador')
                Todas las tareas
            @else
                Mis tareas asignadas
            @endif
        </h2>

        @if($rol === 'Administrador')
            <a href="{{ url('/tareas/crear') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Nueva tarea
            </a>
        @endif
    </div>

    @if($tareas->isEmpty())
        <div class="alert alert-info">
            @if($rol === 'Administrador')
                Aún no hay tareas registradas.
            @else
                No tienes tareas asignadas en este momento.
            @endif
        </div>
    @else
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Contacto</th>
                <th>Descripción</th>
                @if($rol === 'Administrador')
                    <th>Operario</th>
                @endif
                <th>F. realización</th>
                <th>Estado</th>
                <th style="width: 250px;">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach($tareas as $t)
                <tr>
                    <td>{{ $t->id }}</td>
                    <td>{{ $t->cliente?->nombre }}</td>
                    <td>{{ $t->personaNombre }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($t->descripcionTarea, 50) }}</td>
                    @if($rol === 'Administrador')
                        <td>
                            @if($t->operario)
                                {{ $t->operario->nombre }}
                            @else
                                <span class="text-danger">
                                    <i class="fa-solid fa-triangle-exclamation"></i> Sin asignar
                                </span>
                            @endif
                        </td>
                    @endif
                    <td>{{ $t->fechaRealizacion?->format('d/m/Y') }}</td>
                    <td>
                        @php
                            $badgeColor = match($t->estadoTarea) {
                                'P' => 'warning',
                                'R' => 'success',
                                'C' => 'secondary',
                                default => 'light',
                            };
                            $labelEstado = match($t->estadoTarea) {
                                'P' => 'Pendiente',
                                'R' => 'Realizada',
                                'C' => 'Cancelada',
                                default => $t->estadoTarea,
                            };
                        @endphp
                        <span class="badge bg-{{ $badgeColor }} {{ $badgeColor === 'warning' ? 'text-dark' : '' }}">
                            {{ $labelEstado }}
                        </span>
                    </td>
                    <td class="text-nowrap">
                        <a href="{{ url('/tareas/detalle/'.$t->id) }}"
                           class="btn btn-sm btn-secondary" title="Ver">
                            <i class="fa-solid fa-eye"></i>
                        </a>

                        @if($rol === 'Administrador')
                            <a href="{{ url('/tareas/editar/'.$t->id) }}"
                               class="btn btn-sm btn-warning" title="Modificar">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <a href="{{ url('/tareas/eliminar/'.$t->id) }}"
                               class="btn btn-sm btn-danger" title="Eliminar">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        @else
                            {{-- Operario: botón para completar --}}
                            @if($t->estadoTarea === 'P')
                                <a href="{{ url('/tareas/completar/'.$t->id) }}"
                                   class="btn btn-sm btn-success" title="Completar">
                                    <i class="fa-solid fa-check"></i> Completar
                                </a>
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $tareas->links() }}
    @endif
@endsection
