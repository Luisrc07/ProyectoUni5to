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
            $table->id(); // Crea la columna 'id' automáticamente autoincremental y como clave primaria
            $table->string('nombre', 100); // Limita la longitud para mantener orden
            $table->text('descripcion'); // Ideal para contenido extenso
            $table->integer('duracion_estimada_minutos');
            $table->decimal('presupuesto', 10, 2);
            $table->string('estado', 50); // Campo de estado limitado a 50 caracteres
            $table->string('lugar', 100)->nullable(); // Lugar opcional

            // Clave foránea hacia la tabla 'staff', como responsable del proyecto
            // Si el staff se elimina, se establece a null
            $table->foreignId('responsable_id')
                ->nullable()
                ->constrained('staff')
                ->onDelete('set null');

            // Opcional: Clave foránea hacia la tabla 'clientes' si quieres vincular proyectos con clientes
            // $table->foreignId('id_cliente')->constrained('clientes')->onDelete('cascade');

            $table->timestamps(); // Añade 'created_at' y 'updated_at'
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
