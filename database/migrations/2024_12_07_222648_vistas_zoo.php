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
        // Vista: historial_reservas
        DB::statement("
            CREATE VIEW historial_reservas AS
            SELECT re.titulo, re.duracion, r.cantidad
            FROM reservas r
            INNER JOIN horario_recorridos h ON r.id_horario_recorrido = h.id
            INNER JOIN recorridos re ON h.id_recorrido = re.id;
        ");

        // Vista: recorridos_mas_vendidos
        DB::statement("
            CREATE VIEW recorridos_mas_vendidos AS
            SELECT re.titulo, re.precio, re.duracion, COUNT(*) AS veces_vendido
            FROM reservas r
            INNER JOIN horario_recorridos h ON r.id_horario_recorrido = h.id
            INNER JOIN recorridos re ON h.id_recorrido = re.id
            GROUP BY re.id
            ORDER BY veces_vendido DESC;
        ");

        // Vista: compras_usuario_boletos
        DB::statement("
            CREATE VIEW compras_usuario_boletos AS
            SELECT v.id_usuario, b.titulo, v.fecha, v.cantidad, v.precio_total, v.token
            FROM boletos b
            INNER JOIN venta_boletos v ON b.id = v.id_boleto
            ORDER BY v.fecha DESC;
        ");

        // Vista: recorridos_usuario
        DB::statement("
            CREATE VIEW recorridos_usuario AS
            SELECT res.id_usuario, rec.titulo, res.fecha, res.cantidad, res.precio_total, res.token
            FROM recorridos rec
            INNER JOIN horario_recorridos h ON rec.id = h.id_recorrido
            INNER JOIN reservas res ON h.id = res.id_horario_recorrido
            ORDER BY res.fecha DESC;
        ");

        // Vista: ventas_generales
        DB::statement("
            CREATE VIEW ventas_generales AS
            SELECT v.id, b.titulo, v.precio_total, v.cantidad
            FROM boletos b
            INNER JOIN venta_boletos v ON b.id = v.id_boleto
            ORDER BY v.id DESC;
        ");

        // Vista: boletos_vendidos_general
        DB::statement("
            CREATE VIEW boletos_vendidos_general AS
            SELECT b.id, b.titulo, SUM(v.cantidad) AS cantidad
            FROM boletos b
            INNER JOIN venta_boletos v ON b.id = v.id_boleto
            GROUP BY b.id, b.titulo;
        ");

        // Vista: boletos_vendidos_semana
        DB::statement("
            CREATE VIEW boletos_vendidos_semana AS
            SELECT b.id, b.titulo, SUM(v.cantidad) AS cantidad
            FROM boletos b
            INNER JOIN venta_boletos v ON b.id = v.id_boleto
            WHERE v.fecha >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
            GROUP BY b.id, b.titulo;
        ");

        // Vista: boletos_vendidos_mes
        DB::statement("
            CREATE VIEW boletos_vendidos_mes AS
            SELECT b.id, b.titulo, SUM(v.cantidad) AS cantidad
            FROM boletos b
            INNER JOIN venta_boletos v ON b.id = v.id_boleto
            WHERE v.fecha >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
            GROUP BY b.id, b.titulo;
        ");

        // Vista: boletos_vendidos_year
        DB::statement("
            CREATE VIEW boletos_vendidos_year AS
            SELECT b.id, b.titulo, SUM(v.cantidad) AS cantidad
            FROM boletos b
            INNER JOIN venta_boletos v ON b.id = v.id_boleto
            WHERE v.fecha >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)
            GROUP BY b.id, b.titulo;
        ");

        // Vista: recorridos_vendidos_semana
        DB::statement("
            CREATE VIEW recorridos_vendidos_semana AS
            SELECT rec.id, rec.titulo, SUM(res.cantidad) AS ventas
            FROM recorridos rec
            INNER JOIN horario_recorridos h ON rec.id = h.id_recorrido
            INNER JOIN reservas res ON h.id = res.id_horario_recorrido
            WHERE res.fecha >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
            GROUP BY rec.id, rec.titulo;
        ");

        // Vista: recorridos_vendidos_mes
        DB::statement("
            CREATE VIEW recorridos_vendidos_mes AS
            SELECT rec.id, rec.titulo, SUM(res.cantidad) AS ventas
            FROM recorridos rec
            INNER JOIN horario_recorridos h ON rec.id = h.id_recorrido
            INNER JOIN reservas res ON h.id = res.id_horario_recorrido
            WHERE res.fecha >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
            GROUP BY rec.id, rec.titulo;
        ");

        // Vista: recorridos_vendidos_year
        DB::statement("
            CREATE VIEW recorridos_vendidos_year AS
            SELECT rec.id, rec.titulo, SUM(res.cantidad) AS ventas
            FROM recorridos rec
            INNER JOIN horario_recorridos h ON rec.id = h.id_recorrido
            INNER JOIN reservas res ON h.id = res.id_horario_recorrido
            WHERE res.fecha >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)
            GROUP BY rec.id, rec.titulo;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS historial_reservas;");
        DB::statement("DROP VIEW IF EXISTS recorridos_mas_vendidos;");
        DB::statement("DROP VIEW IF EXISTS compras_usuario_boletos;");
        DB::statement("DROP VIEW IF EXISTS recorridos_usuario;");
        DB::statement("DROP VIEW IF EXISTS ventas_generales;");
        DB::statement("DROP VIEW IF EXISTS boletos_vendidos_general;");
        DB::statement("DROP VIEW IF EXISTS boletos_vendidos_semana;");
        DB::statement("DROP VIEW IF EXISTS boletos_vendidos_mes;");
        DB::statement("DROP VIEW IF EXISTS boletos_vendidos_year;");
        DB::statement("DROP VIEW IF EXISTS recorridos_vendidos_semana;");
        DB::statement("DROP VIEW IF EXISTS recorridos_vendidos_mes;");
        DB::statement("DROP VIEW IF EXISTS recorridos_vendidos_year;");
    }
};
