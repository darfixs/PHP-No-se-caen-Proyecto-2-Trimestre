<?php

/**
 * Seeder de provincias de España.
 *
 * Inserta el listado de las 52 provincias con su código INE
 * (que coincide con los dos primeros dígitos del CP).
 *
 * @author  JDAS DWES
 * @version 1.0
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinciasSeeder extends Seeder
{
    public function run(): void
    {
        $provincias = [
            ['codigo' => '01', 'nombre' => 'Álava'],
            ['codigo' => '02', 'nombre' => 'Albacete'],
            ['codigo' => '03', 'nombre' => 'Alicante'],
            ['codigo' => '04', 'nombre' => 'Almería'],
            ['codigo' => '05', 'nombre' => 'Ávila'],
            ['codigo' => '06', 'nombre' => 'Badajoz'],
            ['codigo' => '07', 'nombre' => 'Baleares'],
            ['codigo' => '08', 'nombre' => 'Barcelona'],
            ['codigo' => '09', 'nombre' => 'Burgos'],
            ['codigo' => '10', 'nombre' => 'Cáceres'],
            ['codigo' => '11', 'nombre' => 'Cádiz'],
            ['codigo' => '12', 'nombre' => 'Castellón'],
            ['codigo' => '13', 'nombre' => 'Ciudad Real'],
            ['codigo' => '14', 'nombre' => 'Córdoba'],
            ['codigo' => '15', 'nombre' => 'A Coruña'],
            ['codigo' => '16', 'nombre' => 'Cuenca'],
            ['codigo' => '17', 'nombre' => 'Girona'],
            ['codigo' => '18', 'nombre' => 'Granada'],
            ['codigo' => '19', 'nombre' => 'Guadalajara'],
            ['codigo' => '20', 'nombre' => 'Guipúzcoa'],
            ['codigo' => '21', 'nombre' => 'Huelva'],
            ['codigo' => '22', 'nombre' => 'Huesca'],
            ['codigo' => '23', 'nombre' => 'Jaén'],
            ['codigo' => '24', 'nombre' => 'León'],
            ['codigo' => '25', 'nombre' => 'Lleida'],
            ['codigo' => '26', 'nombre' => 'La Rioja'],
            ['codigo' => '27', 'nombre' => 'Lugo'],
            ['codigo' => '28', 'nombre' => 'Madrid'],
            ['codigo' => '29', 'nombre' => 'Málaga'],
            ['codigo' => '30', 'nombre' => 'Murcia'],
            ['codigo' => '31', 'nombre' => 'Navarra'],
            ['codigo' => '32', 'nombre' => 'Ourense'],
            ['codigo' => '33', 'nombre' => 'Asturias'],
            ['codigo' => '34', 'nombre' => 'Palencia'],
            ['codigo' => '35', 'nombre' => 'Las Palmas'],
            ['codigo' => '36', 'nombre' => 'Pontevedra'],
            ['codigo' => '37', 'nombre' => 'Salamanca'],
            ['codigo' => '38', 'nombre' => 'S. C. Tenerife'],
            ['codigo' => '39', 'nombre' => 'Cantabria'],
            ['codigo' => '40', 'nombre' => 'Segovia'],
            ['codigo' => '41', 'nombre' => 'Sevilla'],
            ['codigo' => '42', 'nombre' => 'Soria'],
            ['codigo' => '43', 'nombre' => 'Tarragona'],
            ['codigo' => '44', 'nombre' => 'Teruel'],
            ['codigo' => '45', 'nombre' => 'Toledo'],
            ['codigo' => '46', 'nombre' => 'Valencia'],
            ['codigo' => '47', 'nombre' => 'Valladolid'],
            ['codigo' => '48', 'nombre' => 'Vizcaya'],
            ['codigo' => '49', 'nombre' => 'Zamora'],
            ['codigo' => '50', 'nombre' => 'Zaragoza'],
            ['codigo' => '51', 'nombre' => 'Ceuta'],
            ['codigo' => '52', 'nombre' => 'Melilla'],
        ];

        // Insert masivo (más rápido que insertar uno a uno)
        DB::table('provincias')->insertOrIgnore($provincias);
    }
}
