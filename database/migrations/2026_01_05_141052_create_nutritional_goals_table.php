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
        Schema::create('nutritional_goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Macros principales
            $table->decimal('calories', 10, 2)->nullable()->default(0);
            $table->decimal('proteins', 10, 2)->nullable()->default(0);
            $table->decimal('fats', 10, 2)->nullable()->default(0);
            $table->decimal('carbohydrates', 10, 2)->nullable()->default(0);

            // Grasas detalladas, fibra y colesterol
            // Usamos decimal en lugar de integer para permitir precisión en gramos (ej. 1.5g)
            $table->decimal('saturated_fat', 10, 2)->nullable()->default(0);
            $table->decimal('trans_fat', 10, 2)->nullable()->default(0);
            $table->decimal('polyunsaturated_fat', 10, 2)->nullable()->default(0);
            $table->decimal('monounsaturated_fat', 10, 2)->nullable()->default(0);
            $table->decimal('fiber', 10, 2)->nullable()->default(0);
            $table->decimal('colesterol', 10, 2)->nullable()->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nutritional_goals');
    }
};