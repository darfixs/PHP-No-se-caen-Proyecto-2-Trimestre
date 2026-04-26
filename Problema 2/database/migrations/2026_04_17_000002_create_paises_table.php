<?php

/**
 * Migración para la tabla de países.
 *
 * Lista de países que se usará en el desplegable de clientes.
 * Cada país tiene su moneda asociada (ej: España → EUR).
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
        Schema::create('paises', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 3)->unique();   // ISO-3166, ej: "ES"
            $table->string('nombre', 100);           // ej: "España"
            $table->string('moneda', 10);            // ej: "EUR"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paises');
    }
};
