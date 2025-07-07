<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones.
     * Crea la tabla 'proyectos' con sus columnas y claves foráneas, sin la relación con 'clientes'.
     */
    public function up(): void
    {
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('nombre');
            $table->string('descripcion');
            $table->date('fecha_inicio'); // Usar date() para fechas
            $table->date('fecha_fin');    // Usar date() para fechas
            $table->decimal('presupuesto', 10, 2); // Usar decimal() para presupuesto
            $table->string('estado');
            $table->string('lugar');

            // Columna y clave foránea para el responsable del proyecto (staff)
            $table->foreignId('responsable_id')->nullable()->constrained('staff')->onDelete('set null'); 
            
            $table->timestamps(); // created_at y updated_at
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
