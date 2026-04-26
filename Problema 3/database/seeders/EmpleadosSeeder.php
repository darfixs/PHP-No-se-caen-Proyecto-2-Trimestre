<?php

/**
 * Seeder de empleados.
 *
 * Crea un administrador y dos operarios iniciales para poder
 * entrar en la aplicación nada más instalarla.
 *
 * Credenciales por defecto:
 *   admin@nosecaen.com / admin123
 *   juan@nosecaen.com  / operario123
 *   ana@nosecaen.com   / operario123
 *
 * @author  Alumno DWES
 * @version 1.0
 */

namespace Database\Seeders;

use App\Models\Empleado;
use Illuminate\Database\Seeder;

class EmpleadosSeeder extends Seeder
{
    public function run(): void
    {
        // Admin principal
        Empleado::firstOrCreate(
            ['correo' => 'admin@nosecaen.com'],
            [
                'dni'        => '00000000A',
                'nombre'     => 'Administrador Principal',
                'telefono'   => '959000000',
                'direccion'  => 'Oficina central',
                'fecha_alta' => now(),
                'tipo'       => 'Administrador',
                'password'   => 'admin123',
            ]
        );

        // Operarios
        Empleado::firstOrCreate(
            ['correo' => 'juan@nosecaen.com'],
            [
                'dni'        => '11111111B',
                'nombre'     => 'Juan Operario',
                'telefono'   => '600111111',
                'direccion'  => 'Calle Falsa 1',
                'fecha_alta' => now(),
                'tipo'       => 'Operario',
                'password'   => 'operario123',
            ]
        );

        Empleado::firstOrCreate(
            ['correo' => 'ana@nosecaen.com'],
            [
                'dni'        => '22222222C',
                'nombre'     => 'Ana Operaria',
                'telefono'   => '600222222',
                'direccion'  => 'Calle Falsa 2',
                'fecha_alta' => now(),
                'tipo'       => 'Operario',
                'password'   => 'operario123',
            ]
        );
    }
}
