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
        Schema::create('contratos', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->unsignedBigInteger('id_cliente')->nullable();
            $table->unsignedBigInteger('id_proyecto')->nullable();
            $table->date('fecha_contrato');
            $table->decimal('costo');
            $table->string('estado');
            $table->string('serial', 5)->unique(); // Eliminado ->after('estado')
            $table->timestamps();
            $table->date('fecha_inicio_proyecto')->nullable();
            $table->date('fecha_fin_proyecto')->nullable();
            $table->foreign('id_cliente')->references('id')->on('clientes')->onDelete('cascade');
            $table->foreign('id_proyecto')->references('id')->on('proyectos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contratos');
    }
};