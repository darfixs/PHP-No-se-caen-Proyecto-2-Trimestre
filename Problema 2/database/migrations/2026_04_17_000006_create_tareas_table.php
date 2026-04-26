<?php

/**
 * Migración para la tabla de tareas / incidencias.
 *
 * Cada tarea es un aviso / encargo de reparación. Puede haberla
 * creado un administrador (asignando operario) o un cliente
 * (y entonces queda sin operario hasta que un admin la asigne).
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
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();

            // Cliente que encarga el trabajo (nuevo requisito del PDF)
            $table->foreignId('cliente_id')
                  ->constrained('clientes')
                  ->restrictOnDelete();

            // Datos de la persona de contacto para esa tarea
            $table->string('personaNombre', 150);
            $table->string('telefono', 30);
            $table->string('correo', 150);

            // Descripción del trabajo a realizar
            $table->text('descripcionTarea');

            // Dirección donde hay que ir
            $table->string('direccionTarea', 200)->nullable();
            $table->string('poblacion', 100)->nullable();
            $table->string('codigoPostal', 5)->nullable();

            // Provincia → código INE (FK a tabla provincias)
            $table->string('provincia', 2)->nullable();
            $table->foreign('provincia')
                  ->references('codigo')
                  ->on('provincias')
                  ->nullOnDelete();

            // Estado: P=Pendiente, R=Realizada, C=Cancelada
            $table->enum('estadoTarea', ['P', 'R', 'C'])->default('P');

            // Operario asignado (empleado de tipo Operario). Puede ser null
            // si el cliente la creó y aún no ha sido asignada por un admin.
            $table->foreignId('operario_id')
                  ->nullable()
                  ->constrained('empleados')
                  ->nullOnDelete();

            // Fechas
            $table->date('fechaRealizacion')->nullable();

            // Anotaciones
            $table->text('anotacionesAnteriores')->nullable();
            $table->text('anotacionesPosteriores')->nullable();

            // Fichero resumen (ruta + nombre original)
            $table->string('ficheroResumen')->nullable();
            $table->string('ficheroNombreOriginal')->nullable();

            // Fecha de creación automática (PDF: "se generará automáticamente")
            // Se usa CURRENT_TIMESTAMP para que sea la BD quien lo ponga.
            $table->timestamp('fechaCreacion')->useCurrent();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tareas');
    }
};
