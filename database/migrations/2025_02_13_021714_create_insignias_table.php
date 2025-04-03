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
        Schema::create('insignias', function (Blueprint $table) {
            $table->id();
            $table->string("nombre",length: 45)->nullable(false);
            $table->string("imagen",length: 255)->nullable(false);
            $table->integer("cantidad")->nullable(false);
            $table->boolean('estado')->nullable(false)->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insignias');
    }
};
