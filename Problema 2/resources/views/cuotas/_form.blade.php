@csrf

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Cliente</label>
            @php $clienteActual = old('cliente_id', $cuota->cliente_id ?? ''); @endphp
            <select name="cliente_id"
                    class="form-select @error('cliente_id') is-invalid @enderror"
                    id="cliente_id" required>
                <option value="">-- Seleccionar cliente --</option>
                @foreach($clientes as $cli)
                    <option value="{{ $cli->id }}"
                            data-importe="{{ $cli->importe_cuota }}"
                            data-moneda="{{ $cli->moneda }}"
                            @selected($clienteActual == $cli->id)>
                        {{ $cli->nombre }} ({{ $cli->cif }})
                    </option>
                @endforeach
            </select>
            @error('cliente_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Concepto</label>
            <input type="text" name="concepto"
                   class="form-control @error('concepto') is-invalid @enderror"
                   value="{{ old('concepto', $cuota->concepto ?? 'Cuota mensual mantenimiento') }}" required>
            @error('concepto')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha de emisión</label>
            <input type="date" name="fecha_emision"
                   class="form-control @error('fecha_emision') is-invalid @enderror"
                   value="{{ old('fecha_emision', isset($cuota) ? $cuota->fecha_emision?->format('Y-m-d') : date('Y-m-d')) }}" required>
            @error('fecha_emision')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Importe</label>
            <input type="number" step="0.01" min="0" name="importe" id="importe"
                   class="form-control @error('importe') is-invalid @enderror"
                   value="{{ old('importe', $cuota->importe ?? '0.00') }}" required>
            @error('importe')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Moneda</label>
            <input type="text" name="moneda" id="moneda" maxlength="3"
                   class="form-control @error('moneda') is-invalid @enderror"
                   value="{{ old('moneda', $cuota->moneda ?? 'EUR') }}" required>
            @error('moneda')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="form-check mb-3">
            <input type="hidden" name="pagada" value="0">
            <input type="checkbox" name="pagada" value="1" id="pagada"
                   class="form-check-input"
                   @checked(old('pagada', $cuota->pagada ?? false))>
            <label class="form-check-label" for="pagada">Cuota pagada</label>
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha de pago</label>
            <input type="date" name="fecha_pago"
                   class="form-control"
                   value="{{ old('fecha_pago', isset($cuota) ? $cuota->fecha_pago?->format('Y-m-d') : '') }}">
        </div>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Notas</label>
    <textarea name="notas" rows="3" class="form-control">{{ old('notas', $cuota->notas ?? '') }}</textarea>
</div>

<button type="submit" class="btn btn-primary">
    <i class="fa-solid fa-save"></i> Guardar
</button>
<a href="{{ url('/cuotas') }}" class="btn btn-secondary">
    <i class="fa-solid fa-arrow-left"></i> Volver
</a>

{{-- Al seleccionar cliente, relleno importe y moneda con los del cliente --}}
<script>
    document.getElementById('cliente_id')?.addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];
        const importe = opt?.getAttribute('data-importe');
        const moneda  = opt?.getAttribute('data-moneda');
        if (importe) document.getElementById('importe').value = importe;
        if (moneda)  document.getElementById('moneda').value  = moneda;
    });
</script>
