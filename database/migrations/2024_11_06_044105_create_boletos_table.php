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
<<<<<<<< HEAD:database/migrations/2024_11_06_044105_create_boletos_table.php
        Schema::create('boletos', function (Blueprint $table) {
            $table->id();
            $table->string("titulo",length: 80);
            $table->string("descripcion",length: 45);
            $table->decimal("precio");

            
========
        Schema::create('guias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_completo', 200);
            $table->boolean('disponible')->default(1);
>>>>>>>> dev_juan:database/migrations/2024_11_14_021818_create_guias_table.php
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
<<<<<<<< HEAD:database/migrations/2024_11_06_044105_create_boletos_table.php
        Schema::dropIfExists('boletos');
========
        Schema::dropIfExists('guias');
>>>>>>>> dev_juan:database/migrations/2024_11_14_021818_create_guias_table.php
    }
};
