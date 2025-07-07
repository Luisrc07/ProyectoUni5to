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
            $table->string('nombre');
            $table->string('descripcion')->nullable(); 
            $table->string('marca'); 
            $table->string('tipo_equipo');
            $table->string('estado'); 
            $table->string('ubicacion');
            $table->integer('cantidad');
            $table->decimal('valor');
            $table->foreignId('responsable')->nullable()->constrained('staff')->onDelete('set null'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipos');
    }
};