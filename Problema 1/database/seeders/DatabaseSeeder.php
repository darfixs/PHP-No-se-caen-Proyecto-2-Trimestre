<?php

/**
 * Seeder principal.
 *
 * Simplemente llama al resto de seeders en el orden correcto
 * (paises y provincias primero, porque clientes/tareas dependen de ellos).
 *
 * @author  JDAS DWES
 * @version 1.0
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ProvinciasSeeder::class,
            PaisesSeeder::class,
            EmpleadosSeeder::class,
            ClientesSeeder::class,
        ]);
    }
}
