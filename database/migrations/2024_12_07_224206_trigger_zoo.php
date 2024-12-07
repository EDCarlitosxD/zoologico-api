<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE TRIGGER actualizar_disponibilidad
            AFTER INSERT ON reservas
            FOR EACH ROW
            BEGIN
                UPDATE horario_recorridos
                SET disponible = 0
                WHERE id = NEW.id_horario_recorrido;
            END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS actualizar_disponibilidad');
    }
};
