@extends('layout.base')

@section('titulo', 'Cuotas · Nosecaen')

@section('contenido')
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h2 class="mb-0"><i class="fa-solid fa-euro-sign"></i> Listado de cuotas</h2>

        <div class="d-flex gap-2">
            <a href="{{ url('/cuotas/crear') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Cuota excepcional
            </a>

            {{-- Generar remesa mensual (una cuota por cada cliente) --}}
            <form method="post" action="{{ url('/cuotas/remesa') }}"
                  onsubmit="return confirm('¿Generar una cuota mensual para TODOS los clientes?');">
                @csrf
                <button type="submit" class="btn btn-success">
                    <i class="fa-solid fa-rotate"></i> Generar remesa mensual
                </button>
            </form>
        </div>
    </div>

    @if($cuotas->isEmpty())
        <div class="alert alert-info">No hay cuotas todavía.</div>
    @else
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-dark">
            <tr>
                <th>Id</th>
                <th>Cliente</th>
                <th>Concepto</th>
                <th>Fecha emisión</th>
                <th>Importe</th>
                <th>Estado</th>
                <th style="width: 260px;">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach($cuotas as $c)
                <tr>
                    <td>{{ $c->id }}</td>
                    <td>{{ $c->cliente?->nombre }}</td>
                    <td>{{ $c->concepto }}</td>
                    <td>{{ $c->fecha_emision?->format('d/m/Y') }}</td>
                    <td>{{ number_format($c->importe, 2, ',', '.') }} {{ $c->moneda }}</td>
                    <td>
                        @if($c->pagada)
                            <span class="badge bg-success">Pagada</span>
                        @else
                            <span class="badge bg-warning text-dark">Pendiente</span>
                        @endif
                    </td>
                    <td class="text-nowrap">
                        <a href="{{ url('/facturas/mostrar/'.$c->id) }}"
                           target="_blank"
                           class="btn btn-sm btn-outline-primary"
                           title="Ver factura">
                            <i class="fa-solid fa-file-pdf"></i>
                        </a>
                        <a href="{{ url('/facturas/descargar/'.$c->id) }}"
                           class="btn btn-sm btn-outline-success"
                           title="Descargar factura">
                            <i class="fa-solid fa-download"></i>
                        </a>
                        <form method="post" action="{{ url('/cuotas/reenviar/'.$c->id) }}" class="d-inline"
                              onsubmit="return confirm('¿Reenviar correo con la factura al cliente?');">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-info"
                                    title="Reenviar correo">
                                <i class="fa-solid fa-envelope"></i>
                            </button>
                        </form>
                        <a href="{{ url('/cuotas/editar/'.$c->id) }}"
                           class="btn btn-sm btn-warning">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <a href="{{ url('/cuotas/eliminar/'.$c->id) }}"
                           class="btn btn-sm btn-danger">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $cuotas->links() }}
    @endif
@endsection
