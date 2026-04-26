<?php

/**
 * Migración para crear la tabla de empleados.
 *
 * Los empleados son las personas que acceden a la aplicación.
 * Cada uno puede ser de tipo Administrador u Operario.
 *
 * @author  JDAS DWES
 * @version 1.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /** Crea la tabla empleados con todos los campos que pide el PDF. */
    public function up(): void
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();

            // Datos identificativos del empleado (PDF: DNI, nombre, correo...)
            $table->string('dni', 20)->unique();
            $table->string('nombre', 120);
            $table->string('correo', 150)->unique();
            $table->string('telefono', 20)->nullable();
            $table->string('direccion', 200)->nullable();
            $table->date('fecha_alta');

            // Tipo: "Administrador" u "Operario"
            $table->enum('tipo', ['Administrador', 'Operario'])->default('Operario');

            // Clave hasheada (Laravel auth usa "password" por defecto)
            $table->string('password');

            // Para "Recordarme" de Laravel
            $table->rememberToken();

            $table->timestamps();
        });
    }

    /** Deshace la creación de la tabla. */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
