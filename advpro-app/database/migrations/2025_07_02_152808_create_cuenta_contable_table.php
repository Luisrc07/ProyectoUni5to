<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('cuenta_contable', function (Blueprint $table) {
            $table->id('id_cuenta');
            $table->string('codigo', 20)->unique();
            $table->string('nombre', 100);
            $table->enum('tipo', ['activo', 'pasivo', 'patrimonio', 'ingreso', 'egreso', 'costo']);
            $table->boolean('es_ajustable')->default(false);
            $table->unsignedBigInteger('cuenta_padre_id')->nullable();
            $table->timestamps();

            $table->foreign('cuenta_padre_id')->references('id_cuenta')->on('cuenta_contable')->onDelete('set null');
        });
    }

    public function down(): void {
        Schema::dropIfExists('cuenta_contable');
    }
};
?>