<?php

/**
 * Migración para la tabla de provincias de España.
 *
 * El código se corresponde con los dos primeros dígitos del
 * código postal (indicaciones INE), tal y como pide el PDF.
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
        Schema::create('provincias', function (Blueprint $table) {
            $table->string('codigo', 2)->primary(); // "21" para Huelva, etc.
            $table->string('nombre', 80);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('provincias');
    }
};
