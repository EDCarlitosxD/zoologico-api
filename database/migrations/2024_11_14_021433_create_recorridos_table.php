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
        Schema::create('recorridos', function (Blueprint $table) {
            $table->id();

            $table->string('titulo', 45)->nullable(false);
            $table->decimal('precio', 10, 2)->nullable(false);
            $table->text('descripcion')->nullable(false);
            $table->integer('duracion')->nullable(false);
            $table->integer('cantidad_personas')->nullable(false);
            $table->decimal('precio_persona_extra', 10, 2)->nullable(false);
            $table->boolean("estado")->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recorridos');
    }
};
