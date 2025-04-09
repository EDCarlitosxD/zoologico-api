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
        Schema::create('membresias', function (Blueprint $table) {
            $table->id();
            $table->string("nombre",length: 255)->nullable(false);
            $table->decimal("precio")->nullable(false);
            $table->string("imagen",length: 255)->nullable(false);
            $table->boolean('entradas_ilimitadas')->nullable(false);//*
            $table->unsignedTinyInteger('descuento_alimentos_souvenirs')->nullable(true);//*
            $table->boolean('acceso_eventos')->nullable(true);//*
            $table->unsignedTinyInteger('descuento_tours')->nullable(true);
            $table->boolean('experiencias_animales')->nullable(true);
            $table->boolean('estacionamiento_preferencial')->nullable(true);
            $table->boolean('detras_camaras')->nullable(true);
            $table->boolean('recorrido_vip_gratuito')->nullable(true);
            $table->boolean('programas_conservacion')->nullable(true);
            $table->unsignedTinyInteger('descuento_renta_espacios_eventos')->nullable(true);
            $table->unsignedTinyInteger('precio_especial_invitados')->nullable(true);
            $table->string('regalo_bienvenida', length: 255)->nullable(true);
            $table->boolean('charlas_educativas')->nullable(true);
            $table->boolean('estado')->nullable(false)->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membresias');
    }
};
