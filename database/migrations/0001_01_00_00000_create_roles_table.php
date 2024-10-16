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

        //ROLES
        //Customer
        //Admin-Full
        //AdminTaquilla
        //AdminAnimales
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->char("role");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
