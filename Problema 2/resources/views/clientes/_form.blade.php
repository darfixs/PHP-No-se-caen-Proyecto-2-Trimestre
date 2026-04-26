@csrf

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">CIF</label>
            <input type="text" name="cif"
                   class="form-control @error('cif') is-invalid @enderror"
                   value="{{ old('cif', $cliente->cif ?? '') }}" required>
            @error('cif')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre"
                   class="form-control @error('nombre') is-invalid @enderror"
                   value="{{ old('nombre', $cliente->nombre ?? '') }}" required>
            @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono"
                   class="form-control @error('telefono') is-invalid @enderror"
                   value="{{ old('telefono', $cliente->telefono ?? '') }}" required>
            @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Correo</label>
            <input type="email" name="correo"
                   class="form-control @error('correo') is-invalid @enderror"
                   value="{{ old('correo', $cliente->correo ?? '') }}" required>
            @error('correo')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Cuenta corriente (IBAN)</label>
            <input type="text" name="cuenta_corriente"
                   class="form-control"
                   value="{{ old('cuenta_corriente', $cliente->cuenta_corriente ?? '') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">País</label>
            @php $paisActual = old('pais_id', $cliente->pais_id ?? ''); @endphp
            <select name="pais_id"
                    class="form-select @error('pais_id') is-invalid @enderror"
                    id="pais_id" required>
                <option value="">-- Seleccionar país --</option>
                @foreach($paises as $p)
                    <option value="{{ $p->id }}"
                            data-moneda="{{ $p->moneda }}"
                            @selected($paisActual == $p->id)>
                        {{ $p->nombre }} ({{ $p->moneda }})
                    </option>
                @endforeach
            </select>
            @error('pais_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Moneda</label>
            <input type="text" name="moneda" id="moneda"
                   class="form-control @error('moneda') is-invalid @enderror"
                   value="{{ old('moneda', $cliente->moneda ?? 'EUR') }}"
                   maxlength="3" required>
            @error('moneda')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <small class="text-muted">Se rellena automáticamente al seleccionar país.</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Importe cuota mensual</label>
            <input type="number" name="importe_cuota" step="0.01" min="0"
                   class="form-control @error('importe_cuota') is-invalid @enderror"
                   value="{{ old('importe_cuota', $cliente->importe_cuota ?? '0.00') }}" required>
            @error('importe_cuota')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

<div class="mt-2">
    <button type="submit" class="btn btn-primary">
        <i class="fa-solid fa-save"></i> Guardar
    </button>
    <a href="{{ url('/clientes') }}" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Volver
    </a>
</div>

{{-- Pequeño script: al cambiar el país, rellena la moneda --}}
<script>
    document.getElementById('pais_id')?.addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];
        const moneda = opt?.getAttribute('data-moneda');
        if (moneda) {
            document.getElementById('moneda').value = moneda;
        }
    });
</script>
