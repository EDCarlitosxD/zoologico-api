<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('descuentos_aplicados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_reserva');
            $table->decimal('precio_original', 10, 2);
            $table->decimal('precio_con_descuento', 10, 2);
            $table->decimal('valor_descuento', 10, 2);
            $table->integer('porcentaje_descuento');
            $table->string('tipo_membresia')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('id_reserva')->references('id')->on('reservas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('descuentos_aplicados');
    }
};
