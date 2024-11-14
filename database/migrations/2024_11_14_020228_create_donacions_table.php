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
        Schema::create('donaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_usuario')->nullable(true);
            $table->decimal('monto', 10, 2)->nullable(false);
            $table->text('mensaje')->nullable(false);
            $table->string('metodo_pago', 50)->nullable(false);
            $table->string('correo', 255)->nullable(false);
            $table->string('recibo', 45);


            // Índices y claves foráneas
            $table->foreign('id_usuario')
                  ->references('id')
                  ->on('usuarios')
                  ->onDelete('set null')
                  ->onUpdate('cascade');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donaciones');
    }
};
