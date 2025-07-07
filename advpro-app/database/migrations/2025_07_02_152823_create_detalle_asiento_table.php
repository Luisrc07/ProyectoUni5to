<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_detalle_asiento_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('detalle_asiento', function (Blueprint $table) {
            $table->id('id_detalle');
            $table->unsignedBigInteger('id_asiento');
            $table->unsignedBigInteger('id_cuenta');
            $table->decimal('debe', 12, 2)->default(0);
            $table->decimal('haber', 12, 2)->default(0);
            $table->text('descripcion_linea')->nullable();
            $table->timestamps();

            $table->foreign('id_asiento')->references('id_asiento')->on('asiento_contable')->onDelete('cascade');
            $table->foreign('id_cuenta')->references('id_cuenta')->on('cuenta_contable')->onDelete('restrict');
        });
    }

    public function down(): void {
        Schema::dropIfExists('detalle_asiento');
    }
};
?>