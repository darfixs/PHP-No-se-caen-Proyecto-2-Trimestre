<?php

/**
 * Seeder de países.
 *
 * Inserta unos cuantos países con su moneda. Se usan en el
 * desplegable del alta de clientes.
 *
 * @author  Alumno DWES
 * @version 1.0
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaisesSeeder extends Seeder
{
    public function run(): void
    {
        $paises = [
            ['codigo' => 'ES', 'nombre' => 'España',          'moneda' => 'EUR'],
            ['codigo' => 'FR', 'nombre' => 'Francia',         'moneda' => 'EUR'],
            ['codigo' => 'IT', 'nombre' => 'Italia',          'moneda' => 'EUR'],
            ['codigo' => 'PT', 'nombre' => 'Portugal',        'moneda' => 'EUR'],
            ['codigo' => 'DE', 'nombre' => 'Alemania',        'moneda' => 'EUR'],
            ['codigo' => 'GB', 'nombre' => 'Reino Unido',     'moneda' => 'GBP'],
            ['codigo' => 'US', 'nombre' => 'Estados Unidos',  'moneda' => 'USD'],
            ['codigo' => 'MX', 'nombre' => 'México',          'moneda' => 'MXN'],
            ['codigo' => 'AR', 'nombre' => 'Argentina',       'moneda' => 'ARS'],
            ['codigo' => 'CO', 'nombre' => 'Colombia',        'moneda' => 'COP'],
            ['codigo' => 'CH', 'nombre' => 'Suiza',           'moneda' => 'CHF'],
            ['codigo' => 'JP', 'nombre' => 'Japón',           'moneda' => 'JPY'],
        ];

        DB::table('paises')->insertOrIgnore($paises);
    }
}
