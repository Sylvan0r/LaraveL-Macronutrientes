<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_consumptions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            // Porciones consumidas
            $table->decimal('quantity', 8, 2)->default(1);

            // Día de consumo
            $table->date('date');

            $table->timestamps();

            // 🔑 CLAVE PARA QUE TODO FUNCIONE BIEN
            $table->unique(['user_id', 'product_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_consumptions');
    }
};
