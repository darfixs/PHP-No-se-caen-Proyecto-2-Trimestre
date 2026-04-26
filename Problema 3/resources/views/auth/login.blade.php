@extends('layout.base')

@section('titulo', 'Iniciar sesión · Nosecaen S.L.')

@section('contenido')
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <h2 class="mb-3 text-center">
                <i class="fa-solid fa-right-to-bracket"></i> Iniciar sesión
            </h2>
            <p class="text-muted small text-center mb-4">
                Introduce tu correo y contraseña para acceder.
            </p>

            {{-- Errores --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="post" action="{{ url('login') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Correo</label>
                    <input type="email" name="correo" class="form-control"
                           value="{{ old('correo') }}" required autofocus>
                </div>

                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox"
                           id="recordarme" name="recordarme" value="1">
                    <label class="form-check-label" for="recordarme">
                        Recordarme
                    </label>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="fa-solid fa-right-to-bracket"></i> Entrar
                </button>
            </form>

            <hr class="my-4">
            <div class="text-center small">
                ¿Eres cliente y quieres reportar una incidencia?<br>
                <a href="{{ url('/incidencia') }}">Registrar una incidencia</a>
            </div>
        </div>
    </div>
@endsection
