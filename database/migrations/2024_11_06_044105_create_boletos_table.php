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
        Schema::create('boletos', function (Blueprint $table) {
            $table->id();
            $table->string("titulo",length: 80)->nullable(false);
            $table->string("descripcion_card",length: 45)->nullable(false);
            $table->text("descripcion")->nullable(false);
            $table->text("advertencias")->nullable(false);
            $table->decimal("precio")->nullable(false);
            $table->boolean('estado')->nullable(false)->default(true);
            $table->string("imagen", 255)->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boletos');
    }
};
