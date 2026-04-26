@extends('layout.base')

@section('titulo', 'Completar tarea · Nosecaen')

@section('contenido')
    <h2><i class="fa-solid fa-check-double"></i> Completar tarea #{{ $tarea->id }}</h2>

    <div class="alert alert-secondary small">
        <strong>Cliente:</strong> {{ $tarea->cliente?->nombre }}<br>
        <strong>Descripción:</strong> {{ $tarea->descripcionTarea }}<br>
        <strong>Fecha realización:</strong> {{ $tarea->fechaRealizacion?->format('d/m/Y') }}
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
            </ul>
        </div>
    @endif

    {{-- enctype="multipart/form-data" para permitir subir fichero --}}
    <form method="post" action="{{ url('/tareas/completar/'.$tarea->id) }}"
          enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Estado</label>
            <select name="estadoTarea" class="form-select" required>
                <option value="R" selected>Realizada</option>
                <option value="C">Cancelada</option>
                <option value="P">Pendiente (no cambiar)</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Anotaciones posteriores</label>
            <textarea name="anotacionesPosteriores" rows="4"
                      class="form-control"
                      placeholder="Indica aquí lo que has hecho, materiales, incidencias, etc."
            >{{ old('anotacionesPosteriores') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">
                Fichero resumen (opcional)
                <small class="text-muted">(PDF, imagen, Word... máx. 5 MB)</small>
            </label>
            <input type="file" name="ficheroResumen"
                   class="form-control @error('ficheroResumen') is-invalid @enderror"
                   accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.txt">
            @error('ficheroResumen')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <button type="submit" class="btn btn-success">
            <i class="fa-solid fa-check"></i> Marcar como completada
        </button>
        <a href="{{ url('/tareas') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Cancelar
        </a>
    </form>
@endsection
