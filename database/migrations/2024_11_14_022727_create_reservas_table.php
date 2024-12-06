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
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_usuario');
            $table->integer('cantidad_personas')->nullable();
            $table->unsignedBigInteger('id_horario_recorrido');
            $table->date('fecha');
            $table->decimal('precio_total', );
            $table->boolean("estado")->default(1);
            $table->string('token', length: 255);

            // Índices y claves foráneas
            $table->foreign('id_usuario')
                  ->references('id')
                  ->on('usuarios')
                  ->onDelete('no action')
                  ->onUpdate('cascade');
            $table->foreign('id_horario_recorrido')
                  ->references('id')
                  ->on('horario_recorridos')
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
        Schema::dropIfExists('reservas');
    }
};
