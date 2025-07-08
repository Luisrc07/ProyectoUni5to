<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones.
     * Crea la tabla 'proyecto_recursos' para asignar personal y equipos a proyectos.
     */
    public function up(): void
    {
        Schema::create('proyecto_recursos', function (Blueprint $table) {
            $table->id();
            // Clave foránea al proyecto
            $table->foreignId('proyecto_id')->constrained('proyectos')->onDelete('cascade');

            // Columnas para la relación polimórfica:
            // 'asignable_id' (unsignedBigInteger) y 'asignable_type' (string)
            $table->morphs('asignable'); 

            // Atributos específicos de la asignación
            $table->integer('cantidad')->nullable(); // Cantidad para equipos (null para personal)
      

            $table->timestamps();

            // Índices para mejorar el rendimiento de las consultas
            $table->index('proyecto_id');
            $table->index(['asignable_id', 'asignable_type']);
        });
    }

    /**
     * Revierte las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyecto_recursos');
    }
};
