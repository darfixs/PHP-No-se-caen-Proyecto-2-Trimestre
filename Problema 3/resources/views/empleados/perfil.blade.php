@extends('layout.base')

@section('titulo', 'Mi perfil · Nosecaen')

@section('contenido')
    <h2><i class="fa-solid fa-user-gear"></i> Mi perfil</h2>

    <p class="text-muted small">
        Aquí puedes modificar tu correo y tu fecha de alta, tal y como
        permite la aplicación a todos los empleados.
    </p>

    <form method="post" action="{{ url('/perfil') }}" class="row g-3">
        @csrf

        <div class="col-md-6">
            <label class="form-label">DNI</label>
            <input type="text" class="form-control" value="{{ $empleado->dni }}" disabled>
        </div>

        <div class="col-md-6">
            <label class="form-label">Nombre</label>
            <input type="text" class="form-control" value="{{ $empleado->nombre }}" disabled>
        </div>

        <div class="col-md-6">
            <label class="form-label">Correo</label>
            <input type="email" name="correo"
                   class="form-control @error('correo') is-invalid @enderror"
                   value="{{ old('correo', $empleado->correo) }}" required>
            @error('correo')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Fecha de alta</label>
            <input type="date" name="fecha_alta"
                   class="form-control @error('fecha_alta') is-invalid @enderror"
                   value="{{ old('fecha_alta', $empleado->fecha_alta?->format('Y-m-d')) }}" required>
            @error('fecha_alta')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-save"></i> Guardar cambios
            </button>
        </div>
    </form>
@endsection
