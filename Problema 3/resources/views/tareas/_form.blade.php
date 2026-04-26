@csrf

<div class="row">
    <div class="col-md-6">

        {{-- Cliente --}}
        <div class="mb-3">
            <label class="form-label">Cliente</label>
            @php $clienteActual = old('cliente_id', $tarea->cliente_id ?? ''); @endphp
            <select name="cliente_id"
                    class="form-select @error('cliente_id') is-invalid @enderror" required>
                <option value="">-- Seleccionar cliente --</option>
                @foreach($clientes as $cli)
                    <option value="{{ $cli->id }}"
                            @selected($clienteActual == $cli->id)>
                        {{ $cli->nombre }} ({{ $cli->cif }})
                    </option>
                @endforeach
            </select>
            @error('cliente_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- Persona de contacto --}}
        <div class="mb-3">
            <label class="form-label">Persona de contacto</label>
            <input type="text" name="personaNombre"
                   class="form-control @error('personaNombre') is-invalid @enderror"
                   value="{{ old('personaNombre', $tarea->personaNombre ?? '') }}" required>
            @error('personaNombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- Teléfono --}}
        <div class="mb-3">
            <label class="form-label">Teléfono de contacto</label>
            <input type="text" name="telefono"
                   class="form-control @error('telefono') is-invalid @enderror"
                   value="{{ old('telefono', $tarea->telefono ?? '') }}" required>
            @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- Correo --}}
        <div class="mb-3">
            <label class="form-label">Correo electrónico</label>
            <input type="email" name="correo"
                   class="form-control @error('correo') is-invalid @enderror"
                   value="{{ old('correo', $tarea->correo ?? '') }}" required>
            @error('correo')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- Descripción --}}
        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcionTarea" rows="3"
                      class="form-control @error('descripcionTarea') is-invalid @enderror"
                      required>{{ old('descripcionTarea', $tarea->descripcionTarea ?? '') }}</textarea>
            @error('descripcionTarea')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

    </div>

    <div class="col-md-6">

        {{-- Dirección --}}
        <div class="mb-3">
            <label class="form-label">Dirección</label>
            <input type="text" name="direccionTarea"
                   class="form-control"
                   value="{{ old('direccionTarea', $tarea->direccionTarea ?? '') }}">
        </div>

        {{-- Población --}}
        <div class="mb-3">
            <label class="form-label">Población</label>
            <input type="text" name="poblacion"
                   class="form-control"
                   value="{{ old('poblacion', $tarea->poblacion ?? '') }}">
        </div>

        {{-- Código postal --}}
        <div class="mb-3">
            <label class="form-label">Código postal</label>
            <input type="text" name="codigoPostal" maxlength="5"
                   class="form-control @error('codigoPostal') is-invalid @enderror"
                   value="{{ old('codigoPostal', $tarea->codigoPostal ?? '') }}">
            @error('codigoPostal')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- Provincia --}}
        <div class="mb-3">
            <label class="form-label">Provincia</label>
            @php $provActual = old('provincia', $tarea->provincia ?? ''); @endphp
            <select name="provincia"
                    class="form-select @error('provincia') is-invalid @enderror">
                <option value="">-- Seleccionar provincia --</option>
                @foreach($provincias as $p)
                    <option value="{{ $p->codigo }}" @selected($provActual === $p->codigo)>
                        {{ $p->codigo }} - {{ $p->nombre }}
                    </option>
                @endforeach
            </select>
            @error('provincia')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- Operario (admin: obligatorio) --}}
        <div class="mb-3">
            <label class="form-label">Operario asignado</label>
            @php $opActual = old('operario_id', $tarea->operario_id ?? ''); @endphp
            <select name="operario_id"
                    class="form-select @error('operario_id') is-invalid @enderror" required>
                <option value="">-- Seleccionar operario --</option>
                @foreach($operarios as $op)
                    <option value="{{ $op->id }}" @selected($opActual == $op->id)>
                        {{ $op->nombre }}
                    </option>
                @endforeach
            </select>
            @error('operario_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- Estado --}}
        <div class="mb-3">
            <label class="form-label">Estado</label>
            @php $est = old('estadoTarea', $tarea->estadoTarea ?? 'P'); @endphp
            <select name="estadoTarea"
                    class="form-select @error('estadoTarea') is-invalid @enderror" required>
                <option value="P" @selected($est === 'P')>Pendiente (P)</option>
                <option value="R" @selected($est === 'R')>Realizada (R)</option>
                <option value="C" @selected($est === 'C')>Cancelada (C)</option>
            </select>
            @error('estadoTarea')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- Fecha de realización --}}
        <div class="mb-3">
            <label class="form-label">Fecha de realización</label>
            <input type="date" name="fechaRealizacion"
                   class="form-control @error('fechaRealizacion') is-invalid @enderror"
                   value="{{ old('fechaRealizacion', isset($tarea) ? $tarea->fechaRealizacion?->format('Y-m-d') : '') }}"
                   required>
            @error('fechaRealizacion')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

    </div>
</div>

{{-- Anotaciones anteriores --}}
<div class="mb-3">
    <label class="form-label">Anotaciones anteriores</label>
    <textarea name="anotacionesAnteriores" rows="3"
              class="form-control">{{ old('anotacionesAnteriores', $tarea->anotacionesAnteriores ?? '') }}</textarea>
</div>

<button type="submit" class="btn btn-primary">
    <i class="fa-solid fa-save"></i> Guardar tarea
</button>
<a href="{{ url('/tareas') }}" class="btn btn-secondary">
    <i class="fa-solid fa-arrow-left"></i> Volver
</a>
