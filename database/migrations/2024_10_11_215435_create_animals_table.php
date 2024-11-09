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
            $table->string("nombre",length: 80);
            $table->string("nombre_cientifico",length: 150);
            $table->string("slug", length: 255)->unique();
            $table->string("imagen_principal", length:255);
            $table->string("imagen_secundaria", length: 255);
            $table->text("caracteristicas_fisicas");
            $table->text("dieta");
            $table->text("datos_curiosos");
            $table->text("comportamiento");
            $table->string("peso", length: 45);
            $table->string("altura", length: 45);
            $table->enum('estado', ['activo','inactivo']);
            $table->string('habitat', length: 255);
            $table->text("descripcion");
            $table->string('subtitulo', length:255);
            $table->string('qr', length: 255);
            $table->boolean('eliminado');
            

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
};
