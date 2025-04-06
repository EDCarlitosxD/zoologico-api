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
        Schema::create('membresias_usuarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_membresia')->nullable(false);
            $table->unsignedBigInteger('id_usuario')->nullable(false);
            $table->date('fecha_compra')->nullable(false);
            $table->date('fecha_vencimiento')->nullable(false);
            $table->boolean('estado')->nullable(false)->default(1);
            $table->timestamps();


            // Índices y claves foráneas
            $table->unique('id');
            $table->foreign('id_membresia')
                  ->references('id')
                  ->on('membresias')
                  ->onDelete('no action')
                  ->onUpdate('cascade');

            $table->foreign('id_usuario')
                  ->references('id')
                  ->on('usuarios')
                  ->onDelete('no action')
                  ->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membresias_usuarios');
    }
};
