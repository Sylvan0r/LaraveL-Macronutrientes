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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->integer("calories")->nullable();
            $table->decimal("total_fat",5,2)->nullable();
            $table->decimal("saturated_fat",5,2)->nullable();
            $table->decimal("trans_fat",5,2)->nullable();
            $table->decimal("polyunsaturated_fat",5,2)->nullable();
            $table->decimal("monounsaturated_fat",5,2)->nullable();
            $table->decimal("carbohydrates",5,2)->nullable();
            $table->decimal("sugars",5,2)->nullable();
            $table->decimal("fiber",5,2)->nullable();
            $table->decimal("proteins",5,2)->nullable();
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
