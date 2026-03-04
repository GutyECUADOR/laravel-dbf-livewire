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
        Schema::create('articulos', function (Blueprint $table) {
            $table->id();
            // Identificación básica [cite: 1]
            $table->string('codart', 20)->unique()->comment('Código del Artículo');
            $table->string('nomart', 100)->comment('Nombre del Artículo');
            $table->string('grupo', 20)->nullable();
            $table->string('alterno', 50)->nullable();

            // Impuestos y Medidas [cite: 1, 2]
            $table->string('iva', 5)->nullable();
            $table->string('unidad', 10)->nullable();
            $table->decimal('peso', 12, 4)->default(0);
            $table->string('ubica', 50)->nullable()->comment('Ubicación física');

            // Precios y Utilidades [cite: 1]
            $table->decimal('precio_a', 15, 4)->default(0);
            $table->decimal('precio_b', 15, 4)->default(0);
            $table->decimal('precio_c', 15, 4)->default(0);
            $table->decimal('precio_d', 15, 4)->default(0);

            // Costos y Stock [cite: 3]
            $table->decimal('ult_costo', 15, 4)->default(0);
            $table->decimal('costo_act', 15, 4)->default(0);
            $table->decimal('existe_act', 15, 4)->default(0)->comment('Existencia Actual');
            $table->decimal('exmin', 12, 4)->default(0)->comment('Stock Mínimo');
            $table->decimal('exmax', 12, 4)->default(0)->comment('Stock Máximo');

            // Fechas de control [cite: 3, 6]
            $table->date('fecha_cos')->nullable()->comment('Fecha de Costo');
            $table->date('fecha_sal')->nullable()->comment('Fecha de última salida');
            $table->date('fec_dig')->nullable()->comment('Fecha de digitación');

            // Relaciones técnicas [cite: 4, 5]
            $table->string('codcon', 20)->nullable();
            $table->string('codcos', 20)->nullable();
            $table->string('codbar', 50)->nullable()->comment('Código de Barras');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articulos');
    }
};
