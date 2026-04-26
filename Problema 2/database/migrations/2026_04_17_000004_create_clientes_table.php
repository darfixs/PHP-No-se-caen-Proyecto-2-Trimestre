<?php

/**
 * Migración para la tabla de clientes.
 *
 * Los clientes son las empresas a las que prestamos servicio
 * de mantenimiento de ascensores. Cada mes se les cobra una cuota.
 *
 * @author  JDAS DWES
 * @version 1.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();

            // Identificación fiscal del cliente (PDF: CIF, nombre, teléfono...)
            $table->string('cif', 20)->unique();
            $table->string('nombre', 150);
            $table->string('telefono', 20)->nullable();
            $table->string('correo', 150);
            $table->string('cuenta_corriente', 34)->nullable(); // IBAN

            // País (FK a tabla paises)
            $table->foreignId('pais_id')
                  ->constrained('paises')
                  ->restrictOnDelete();

            // Moneda en la que se emite la cuota (hereda del país pero se guarda por si cambia)
            $table->string('moneda', 10)->default('EUR');

            // Importe mensual que se le cobra
            $table->decimal('importe_cuota', 10, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
