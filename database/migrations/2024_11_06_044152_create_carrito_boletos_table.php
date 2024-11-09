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
        Schema::create('carrito_boletos', function (Blueprint $table) {
            $table->id();
            $table->integer("cantidad");
            $table->foreignId("id_boleto") ->constrained('boletos');
            $table->foreignId("id_usuario")->constrained('users');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carrito_boletos');
    }
};
