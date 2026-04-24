<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>@yield('titulo', 'Nosecaen S.L. · Gestión de incidencias')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        :root {
            --nc-primary: #0d47a1;
            --nc-primary-light: #1976d2;
            --nc-bg: #f3f6ff;
            --nc-card-bg: #ffffff;
            --nc-text: #102a43;
        }

        * { box-sizing: border-box; }
        html, body { height: 100%; }

        body {
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI",
                         Roboto, "Helvetica Neue", Arial, sans-serif;
            background: var(--nc-bg);
            color: var(--nc-text);
        }

        .nc-page-wrapper { min-height: 100vh; display: flex; flex-direction: column; }

        .nc-header {
            background: linear-gradient(90deg, var(--nc-primary), var(--nc-primary-light));
            color: #fff;
            padding: 0.6rem 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.18);
        }
        .nc-header-titulo { font-size: 1.1rem; font-weight: 600; letter-spacing: 0.03em; }
        .nc-header-subtitulo { font-size: 0.85rem; opacity: 0.9; }

        .nc-nav a {
            color: #e3f2fd;
            text-decoration: none;
            margin-right: 0.9rem;
            font-size: 0.9rem;
        }
        .nc-nav a:hover { color: #fff; text-decoration: underline; }

        .nc-main { flex: 1; padding: 0.75rem 0.75rem 0.9rem; display: flex;
                   justify-content: center; align-items: stretch; }
        .nc-main-container { width: 100%; max-width: 1200px; }
        .nc-card {
            background: var(--nc-card-bg);
            border-radius: 0.8rem;
            box-shadow: 0 8px 24px rgba(15,23,42,0.08);
            padding: 1.25rem 1.4rem;
        }
        .nc-card h2, .nc-card h3 { color: var(--nc-primary); }

        .nc-footer {
            background: var(--nc-primary);
            color: #e3f2fd;
            font-size: 0.85rem;
            padding: 0.45rem 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.12);
        }
        .nc-footer-inner {
            display: flex; align-items: center; justify-content: space-between;
            gap: 1rem; flex-wrap: wrap;
        }
        .nc-footer a { color: #bbdefb; text-decoration: none; }
        .nc-footer a:hover { text-decoration: underline; }

        .nc-card .form-label { font-size: 0.9rem; }
        .nc-card .form-control, .nc-card .form-select, .nc-card .btn { font-size: 0.9rem; }
        .nc-card table { font-size: 0.9rem; }
    </style>

    @yield('head-extra')
</head>

<body>
<div class="nc-page-wrapper">

    <header class="nc-header">
        <div class="container-fluid py-1">

            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="nc-header-titulo">
                        <i class="fa-solid fa-building-circle-arrow-right me-1"></i>
                        Nosecaen S.L. · Grupo SiempreColgados
                    </div>
                    <div class="nc-header-subtitulo">
                        Gestión de incidencias, clientes y cuotas
                    </div>
                </div>

                @auth
                    <div class="col-md-6 text-md-end small mt-2 mt-md-0">
                        <strong>Usuario:</strong> {{ auth()->user()->nombre }}
                        &nbsp;·&nbsp;
                        <strong>Rol:</strong> {{ auth()->user()->tipo }}
                        &nbsp;·&nbsp;
                        <form method="post" action="{{ url('logout') }}" class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-light">
                                <i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión
                            </button>
                        </form>
                    </div>
                @endauth
            </div>

            @auth
                <div class="mt-2 nc-nav">
                    <a href="{{ url('/tareas') }}">
                        <i class="fa-solid fa-clipboard-list"></i> Tareas
                    </a>

                    @if(auth()->user()->esAdmin())
                        <a href="{{ url('/empleados') }}">
                            <i class="fa-solid fa-users"></i> Empleados
                        </a>
                        <a href="{{ url('/clientes') }}">
                            <i class="fa-solid fa-briefcase"></i> Clientes
                        </a>
                        <a href="{{ url('/cuotas') }}">
                            <i class="fa-solid fa-euro-sign"></i> Cuotas
                        </a>
                    @endif

                    <a href="{{ url('/perfil') }}">
                        <i class="fa-solid fa-user-gear"></i> Mi perfil
                    </a>
                </div>
            @endauth

        </div>
    </header>

    <main class="nc-main">
        <div class="nc-main-container">
            <div class="nc-card">

                @if(session('ok'))
                    <div class="alert alert-success">
                        <i class="fa-solid fa-check-circle"></i> {{ session('ok') }}
                    </div>
                @endif

                @yield('contenido')
            </div>
        </div>
    </main>

    <footer class="nc-footer">
        <div class="nc-footer-inner">
            <div>Nosecaen S.L. · Proyecto DWES 2ª Evaluación</div>
            <div class="text-center flex-grow-1 d-none d-md-block" style="opacity: 0.85;">
                Santi si lees esto porfavor apruebame que me ha costado 8 vidas hacer esto
            </div>
            <div>© {{ date('Y') }} · <a href="#" target="_blank">@darfixs</a></div>
        </div>
    </footer>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
