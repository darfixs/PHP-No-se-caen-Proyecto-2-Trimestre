<?php
/**
 * Instalador
 *
 *
 * @author  JDAS DWES
 * @version 1.0
 */

$rutaBase = dirname(__DIR__);
$pasos = [];

// Paso 1: crear .env si no existe
if (!file_exists($rutaBase.'/.env')) {
    if (copy($rutaBase.'/.env.example', $rutaBase.'/.env')) {
        $pasos[] = ['ok' => true,  'msg' => 'Fichero .env creado a partir de .env.example.'];
    } else {
        $pasos[] = ['ok' => false, 'msg' => 'No se pudo crear el fichero .env. Hazlo a mano.'];
    }
} else {
    $pasos[] = ['ok' => true, 'msg' => 'El fichero .env ya existe.'];
}

// Paso 2: crear database/database.sqlite si el .env apunta a sqlite
$rutaSqlite = $rutaBase.'/database/database.sqlite';
if (file_exists($rutaBase.'/.env')) {
    $env = file_get_contents($rutaBase.'/.env');
    if (str_contains($env, 'DB_CONNECTION=sqlite') && !file_exists($rutaSqlite)) {
        if (@touch($rutaSqlite)) {
            $pasos[] = ['ok' => true, 'msg' => 'Fichero SQLite creado: database/database.sqlite'];
        } else {
            $pasos[] = ['ok' => false, 'msg' => 'No se pudo crear database/database.sqlite. Hazlo a mano.'];
        }
    }
}

// Paso 3: permisos de storage y bootstrap/cache (Linux)
if (PHP_OS_FAMILY === 'Linux' || PHP_OS_FAMILY === 'Darwin') {
    @chmod($rutaBase.'/storage', 0775);
    @chmod($rutaBase.'/bootstrap/cache', 0775);
    $pasos[] = ['ok' => true, 'msg' => 'Permisos de storage y bootstrap/cache ajustados (775).'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Instalador · Nosecaen S.L.</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f3f6ff; padding: 2rem; }
        .panel { max-width: 780px; margin: 0 auto; background: #fff;
                 padding: 2rem; border-radius: 0.8rem; box-shadow: 0 8px 24px rgba(0,0,0,0.08);}
        pre { background:#101840; color:#fff; padding:1rem; border-radius:0.5rem; }
    </style>
</head>
<body>
<div class="panel">
    <h1>Instalador · Nosecaen S.L.</h1>
    <p class="text-muted">Pequeño asistente de instalación para el proyecto DWES 2ª Ev.</p>

    <h4>Pasos automáticos</h4>
    <ul class="list-group mb-4">
        <?php foreach ($pasos as $p): ?>
            <li class="list-group-item">
                <?php echo $p['ok']
                    ? '<span class="badge bg-success">OK</span>'
                    : '<span class="badge bg-danger">Error</span>'; ?>
                &nbsp; <?php echo htmlspecialchars($p['msg']); ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <h4>Pasos manuales (ejecuta en consola)</h4>
    <ol>
        <li>Instala las dependencias: <pre>composer install</pre></li>
        <li>Genera la clave de la app: <pre>php artisan key:generate</pre></li>
        <li>Lanza las migraciones y seeders: <pre>php artisan migrate --seed</pre></li>
        <li>Crea el enlace simbólico de storage: <pre>php artisan storage:link</pre></li>
        <li>Arranca el servidor: <pre>php artisan serve</pre></li>
    </ol>

    <div class="alert alert-info">
        <strong>Credenciales iniciales:</strong><br>
        - Admin: <code>admin@nosecaen.com</code> / <code>admin123</code><br>
        - Operario: <code>juan@nosecaen.com</code> / <code>operario123</code><br>
        - Operaria: <code>ana@nosecaen.com</code> / <code>operario123</code>
    </div>

    <p class="text-muted small">
        Si quieres usar MySQL/MariaDB en lugar de SQLite, edita tu fichero
        <code>.env</code> antes del <code>migrate</code>.
    </p>
</div>
</body>
</html>
