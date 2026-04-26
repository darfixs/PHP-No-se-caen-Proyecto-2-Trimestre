<?php

/**
 * Seeder de clientes.
 *
 * Crea unos clientes de ejemplo para ver el programa con datos reales.
 *
 * @author  Alumno DWES
 * @version 1.0
 */

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Pais;
use Illuminate\Database\Seeder;

class ClientesSeeder extends Seeder
{
    public function run(): void
    {
        // Saco el ID de España, Francia y Reino Unido para las FK
        $espana = Pais::where('codigo', 'ES')->first();
        $francia = Pais::where('codigo', 'FR')->first();
        $reinoUnido = Pais::where('codigo', 'GB')->first();

        if (!$espana) { return; } // sin países no hay nada que hacer

        Cliente::firstOrCreate(
            ['cif' => 'B11111111'],
            [
                'nombre'           => 'Edificios del Sur S.L.',
                'telefono'         => '959123456',
                'correo'           => 'contacto@edificiossur.com',
                'cuenta_corriente' => 'ES9121000418450200051332',
                'pais_id'          => $espana->id,
                'moneda'           => 'EUR',
                'importe_cuota'    => 120.00,
            ]
        );

        Cliente::firstOrCreate(
            ['cif' => 'B22222222'],
            [
                'nombre'           => 'Ascensores Norte S.A.',
                'telefono'         => '944111222',
                'correo'           => 'info@ascensoresnorte.com',
                'cuenta_corriente' => 'ES1200300203921234567890',
                'pais_id'          => $espana->id,
                'moneda'           => 'EUR',
                'importe_cuota'    => 200.00,
            ]
        );

        if ($francia) {
            Cliente::firstOrCreate(
                ['cif' => 'FR12345678'],
                [
                    'nombre'           => 'Immobilier Paris',
                    'telefono'         => '+33123456789',
                    'correo'           => 'contact@immoparis.fr',
                    'cuenta_corriente' => 'FR1420041010050500013M02606',
                    'pais_id'          => $francia->id,
                    'moneda'           => 'EUR',
                    'importe_cuota'    => 180.00,
                ]
            );
        }

        if ($reinoUnido) {
            Cliente::firstOrCreate(
                ['cif' => 'GB99999999'],
                [
                    'nombre'           => 'London Lifts Ltd.',
                    'telefono'         => '+442012345678',
                    'correo'           => 'info@londonlifts.co.uk',
                    'cuenta_corriente' => 'GB29NWBK60161331926819',
                    'pais_id'          => $reinoUnido->id,
                    'moneda'           => 'GBP',
                    'importe_cuota'    => 150.00,
                ]
            );
        }
    }
}
