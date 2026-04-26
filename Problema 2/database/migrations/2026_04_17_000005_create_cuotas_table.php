<?php

/**
 * Migración para la tabla de cuotas.
 *
 * Cada cuota es un cargo mensual (o excepcional) a un cliente.
 * Desde aquí se generará la factura que se le envía por correo.
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
        Schema::create('cuotas', function (Blueprint $table) {
            $table->id();

            // Cliente al que se le emite la cuota
            $table->foreignId('cliente_id')
                  ->constrained('clientes')
                  ->cascadeOnDelete();

            // Datos de la cuota (PDF: concepto, fecha emisión, importe, pagada, etc.)
            $table->string('concepto', 200)->default('Cuota mensual mantenimiento');
            $table->date('fecha_emision');
            $table->decimal('importe', 10, 2);
            $table->string('moneda', 10)->default('EUR');

            $table->boolean('pagada')->default(false);
            $table->date('fecha_pago')->nullable();
            $table->text('notas')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuotas');
    }
};
