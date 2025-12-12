<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_plato', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_id');
            $table->unsignedBigInteger('plato_id');
            $table->integer('quantity')->default(1); // Cantidad del plato en el menú
            $table->timestamps();

            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
            $table->foreign('plato_id')->references('id')->on('platos')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_plato');
    }
};
