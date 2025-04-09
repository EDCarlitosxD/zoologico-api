<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
     DB::statement("
             CREATE VIEW usuarios_insignias AS
             SELECT 
                 c.id_usuario, 
                 SUM(c.cantidad) AS compras_totales, 
                 i.nombre AS insignia_obtenida, 
                 i.cantidad AS cantidad_insignia,
                 i.`id`,
                 i.`imagen`,
                 i.`estado`
             FROM compras_usuario_recorridos AS c
             JOIN insignias AS i 
                 ON i.cantidad = (
                     SELECT MAX(i2.cantidad)
                     FROM insignias i2
                     WHERE i2.cantidad <= (
                         SELECT SUM(c2.cantidad) 
                         FROM compras_usuario_recorridos c2 
                         WHERE c2.id_usuario = c.id_usuario
                     ) AND i2.`estado` = 1
                 )
             GROUP BY c.id_usuario, i.nombre, i.cantidad;
          ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
    }
};
