@extends('layout.base')

@section('titulo', 'Reportar incidencia · Nosecaen')

@section('contenido')
    <h2><i class="fa-solid fa-bullhorn"></i> Reportar una incidencia</h2>

    <p class="text-muted small">
        Como cliente de Nosecaen S.L. puedes darnos aviso de una incidencia
        rellenando este formulario. Para verificar tu identidad, introduce
        tu CIF y el teléfono con el que te registraste.
    </p>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="post" action="{{ url('/incidencia') }}">
        @csrf

        {{-- Identificación del cliente --}}
        <div class="bg-light p-3 rounded mb-3">
            <h5 class="text-muted">
                <i class="fa-solid fa-id-card"></i> Tus datos de cliente
            </h5>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <label class="form-label">CIF</label>
                    <input type="text" name="cif"
                           class="form-control @error('cif') is-invalid @enderror"
                           value="{{ old('cif') }}" required>
                    @error('cif')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-label">Teléfono registrado</label>
                    <input type="text" name="telefonoCliente"
                           class="form-control @error('telefonoCliente') is-invalid @enderror"
                           value="{{ old('telefonoCliente') }}" required>
                    @error('telefonoCliente')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        {{-- Datos de la incidencia --}}
        <h5 class="text-muted">Datos de la incidencia</h5>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Persona de contacto</label>
                    <input type="text" name="personaNombre"
                           class="form-control @error('personaNombre') is-invalid @enderror"
                           value="{{ old('personaNombre') }}" required>
                    @error('personaNombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Teléfono de contacto</label>
                    <input type="text" name="telefono"
                           class="form-control @error('telefono') is-invalid @enderror"
                           value="{{ old('telefono') }}" required>
                    @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Correo electrónico</label>
                    <input type="email" name="correo"
                           class="form-control @error('correo') is-invalid @enderror"
                           value="{{ old('correo') }}" required>
                    @error('correo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcionTarea" rows="3"
                              class="form-control @error('descripcionTarea') is-invalid @enderror"
                              required>{{ old('descripcionTarea') }}</textarea>
                    @error('descripcionTarea')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Dirección</label>
                    <input type="text" name="direccionTarea"
                           class="form-control" value="{{ old('direccionTarea') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Población</label>
                    <input type="text" name="poblacion"
                           class="form-control" value="{{ old('poblacion') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Código postal</label>
                    <input type="text" name="codigoPostal" maxlength="5"
                           class="form-control @error('codigoPostal') is-invalid @enderror"
                           value="{{ old('codigoPostal') }}">
                    @error('codigoPostal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Provincia</label>
                    <select name="provincia" class="form-select">
                        <option value="">-- Seleccionar --</option>
                        @foreach($provincias as $p)
                            <option value="{{ $p->codigo }}" @selected(old('provincia') === $p->codigo)>
                                {{ $p->codigo }} - {{ $p->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Fecha deseada de realización</label>
                    <input type="date" name="fechaRealizacion"
                           class="form-control @error('fechaRealizacion') is-invalid @enderror"
                           value="{{ old('fechaRealizacion') }}" required>
                    @error('fechaRealizacion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Anotaciones</label>
            <textarea name="anotacionesAnteriores" rows="2"
                      class="form-control">{{ old('anotacionesAnteriores') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-paper-plane"></i> Enviar incidencia
        </button>
        <a href="{{ url('/login') }}" class="btn btn-link">Volver al login</a>
    </form>
@endsection
