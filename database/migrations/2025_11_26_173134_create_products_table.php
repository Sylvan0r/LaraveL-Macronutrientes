<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            // Nutrientes
            $table->integer('calories')->nullable();
            $table->decimal('total_fat',7,2)->nullable();
            $table->decimal('saturated_fat',7,2)->nullable();
            $table->decimal('trans_fat',7,2)->nullable();
            $table->decimal('colesterol',7,2)->nullable();
            $table->decimal('polyunsaturated_fat',7,2)->nullable();
            $table->decimal('monounsaturated_fat',7,2)->nullable();
            $table->decimal('carbohydrates',7,2)->nullable();
            $table->decimal('fiber',7,2)->nullable();
            $table->decimal('proteins',7,2)->nullable();

            // Relación con usuario
            $table->unsignedBigInteger('id_user')->nullable();
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');

            // Relación con categoría            
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

            // ID externo BEDCA
            $table->unsignedBigInteger('external_id')->nullable()->unique();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
