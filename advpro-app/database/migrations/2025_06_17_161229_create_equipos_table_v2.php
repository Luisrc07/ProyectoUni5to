<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('descripcion')->nullable();
            $table->string('marca', 50);
            $table->string('tipo_equipo', 50);
            $table->string('estado', 50);
            $table->string('ubicacion', 100);
            $table->integer('cantidad')->default(1);
            $table->decimal('valor', 10, 2);

            $table->foreignId('responsable_id')
                  ->nullable()
                  ->constrained('staff')
                  ->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Elimina la tabla 'equipos' si existe
        Schema::dropIfExists('equipos');
    }
};
