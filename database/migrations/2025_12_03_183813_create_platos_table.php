<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('descripcion')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // usuario que creó el plato
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platos');
    }
};