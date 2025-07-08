<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones.
     * Crea la tabla 'proyectos' con sus columnas y claves foráneas.
     */
    public function up(): void
    {
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id(); // Crea una columna 'id' auto-incrementable y clave primaria
            $table->string('nombre');
            $table->text('descripcion'); // Cambiado a 'text' para descripciones más largas
            $table->integer('duracion_estimada_minutos'); // Añadido para coincidir con el formulario y el modelo
            $table->decimal('presupuesto', 10, 2); // 'decimal' es ideal para valores monetarios
            $table->string('estado');
            $table->string('lugar')->nullable(); // 'lugar' es opcional en el formulario
          

            // Columna y clave foránea para el responsable del proyecto (staff)
            // Si el staff es eliminado, el responsable_id en proyectos se establecerá a null
            $table->foreignId('responsable_id')->nullable()->constrained('staff')->onDelete('set null');

            $table->timestamps(); // Añade las columnas 'created_at' y 'updated_at'
        });
    }

    /**
     * Revierte las migraciones.
     * Elimina la tabla 'proyectos'.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyectos');
    }
};

