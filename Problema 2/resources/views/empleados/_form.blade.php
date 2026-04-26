
@csrf

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">DNI</label>
            <input type="text" name="dni"
                   class="form-control @error('dni') is-invalid @enderror"
                   value="{{ old('dni', $empleado->dni ?? '') }}" required>
            @error('dni')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre"
                   class="form-control @error('nombre') is-invalid @enderror"
                   value="{{ old('nombre', $empleado->nombre ?? '') }}" required>
            @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Correo</label>
            <input type="email" name="correo"
                   class="form-control @error('correo') is-invalid @enderror"
                   value="{{ old('correo', $empleado->correo ?? '') }}" required>
            @error('correo')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono"
                   class="form-control @error('telefono') is-invalid @enderror"
                   value="{{ old('telefono', $empleado->telefono ?? '') }}">
            @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Dirección</label>
            <input type="text" name="direccion"
                   class="form-control"
                   value="{{ old('direccion', $empleado->direccion ?? '') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha de alta</label>
            <input type="date" name="fecha_alta"
                   class="form-control @error('fecha_alta') is-invalid @enderror"
                   value="{{ old('fecha_alta', isset($empleado) ? $empleado->fecha_alta?->format('Y-m-d') : date('Y-m-d')) }}" required>
            @error('fecha_alta')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Tipo</label>
            @php $tipoActual = old('tipo', $empleado->tipo ?? 'Operario'); @endphp
            <select name="tipo" class="form-select">
                <option value="Operario"     @selected($tipoActual === 'Operario')>Operario</option>
                <option value="Administrador" @selected($tipoActual === 'Administrador')>Administrador</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">
                Contraseña
                @if($esEdicion)
                    <small class="text-muted">(déjala vacía para no cambiarla)</small>
                @endif
            </label>
            <input type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   {{ $esEdicion ? '' : 'required' }}>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

<div class="mt-3">
    <button type="submit" class="btn btn-primary">
        <i class="fa-solid fa-save"></i> Guardar
    </button>
    <a href="{{ url('/empleados') }}" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Volver
    </a>
</div>
