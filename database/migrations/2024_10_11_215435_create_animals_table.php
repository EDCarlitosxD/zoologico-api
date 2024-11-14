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
        Schema::create('animales', function (Blueprint $table) {
            $table->id();
            $table->char("nombre",length: 255);
            $table->char("nombre_cientifico",length: 255);
            $table->char("slug",255)->unique();
            $table->text("caracteristicas_fisicas");
            $table->text("dieta");
            $table->text("datos_curiosos");
            $table->text("comportamiento");
            $table->text("informacion");
            $table->text("imagen_principal");
            $table->text("imagen_secundaria");
            $table->boolean("activo");
            $table->enum("tipo",["acuático", "terrestre", "aéreo", "anfibio"]);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animales');
    }
};
