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
        Schema::create('horario_recorridos', function (Blueprint $table) {
            $table->id();
            $table->time('horario_inicio');
            $table->boolean('disponible')->default(1);
            $table->unsignedBigInteger('id_recorrido');
            $table->unsignedBigInteger('id_guia');
            $table->date('fecha');
            $table->time('horario_fin');

            // Índices y claves foráneas
            $table->unique('id');
            $table->foreign('id_recorrido')
                  ->references('id')
                  ->on('recorridos')
                  ->onDelete('no action')
                  ->onUpdate('cascade');
            $table->foreign('id_guia')
                  ->references('id')
                  ->on('guias')
                  ->onDelete('no action')
                  ->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horario_recorridos');
    }
};
