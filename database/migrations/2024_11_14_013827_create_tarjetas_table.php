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
        Schema::create('tarjetas', function (Blueprint $table) {
            $table->id();
            $table->char("fecha_expiracion",255)->nullable(false);
            $table->char("banco",255)->nullable(false);
            $table->char("numero_tarjeta",255)->nullable(false);
            $table->char("nombre_tarjeta",255)->nullable(false);
            $table->char("cvv",5)->nullable(false);
            $table->char("tipo_tarjeta",255)->nullable(false);

            $table->unsignedBigInteger('id_usuario')->nullable(false);
            $table->foreign('id_usuario')
            ->references('id')
            ->on('usuarios')
            ->onDelete('cascade')
            ->onUpdate('cascade');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarjetas');
    }
};
