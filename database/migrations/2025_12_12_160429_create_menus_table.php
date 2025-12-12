<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre del menú
            $table->unsignedBigInteger('user_id'); // Usuario que crea el menú
            $table->integer('calories')->default(0);
            $table->integer('proteins')->default(0);
            $table->integer('carbohydrates')->default(0);
            $table->integer('fats')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
