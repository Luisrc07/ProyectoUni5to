<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('asiento_contable', function (Blueprint $table) {
            $table->id('id_asiento');
            $table->date('fecha');
            $table->text('descripcion');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('asiento_contable');
    }
};

?>
