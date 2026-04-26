<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Bootstrap 5 (lo mantengo para no romper la estética del resto de la app) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <title inertia>Nosecaen S.L. · Problema 3.3</title>

    {{-- 
        Directiva @routes NO la uso (requiere el paquete Ziggy configurado).
        En su lugar pinto la URL base y la inyecto por axios.
    --}}

    {{-- Vite: carga los assets compilados en desarrollo / producción --}}
    @vite(['resources/js/app.js'])

    {{-- Inertia usa @inertiaHead para títulos dinámicos --}}
    @inertiaHead
</head>
<body style="background: #f3f6ff;">

    {{-- Este es el "punto de montaje" del SPA Vue --}}
    @inertia

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
