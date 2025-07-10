<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('equipos', function (Blueprint $table) {
        $table->id(); 
        $table->string('nombre', 100); // Limita si quieres mantener la base más controlada
        $table->string('descripcion')->nullable(); 
        $table->string('marca', 50); 
        $table->string('tipo_equipo', 50);
        $table->string('estado', 50); 
        $table->string('ubicacion', 100);
        $table->integer('cantidad')->default(1); // Opcional: valor por defecto
        $table->decimal('valor', 10, 2); // Aquí está la corrección clave
        $table->foreignId('responsable_id') // Renombrado para seguir convención
              ->nullable()
              ->constrained('staff')
              ->onDelete('set null'); 
        $table->timestamps();
        });

    }

 
    public function down(): void
    {
        Schema::dropIfExists('equipos');
    }
};