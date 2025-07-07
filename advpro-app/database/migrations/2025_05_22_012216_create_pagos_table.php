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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->unsignedBigInteger('id_contrato')->nullable();
            $table->date('fecha_pago');
            $table->string('monto');
            $table->string('metodo_pago');
            $table->string('estado')->default('Pendiente');
            $table->timestamps();
            $table->foreign('id_contrato')->references('id')->on('contratos')->onDelete('cascade');
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
