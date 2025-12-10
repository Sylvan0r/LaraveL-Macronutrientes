<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('plato_product', function (Blueprint $table) {
            $table->foreignId('plato_id')->constrained('platos')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('quantity')->default(1); // aquí la cantidad de cada producto
            $table->timestamps();
            
            $table->unique(['plato_id', 'product_id']); // evita duplicados
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plato_product');
    }
};
