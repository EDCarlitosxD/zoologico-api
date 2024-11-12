<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //ROLES
        //Customer
        //Admin-Full
        //AdminTaquilla
        //AdminAnimales
        DB::table('roles')->insert([
            ['role' => 'admin'],
            ['role' => 'customer'],
            ['role' => 'taquilla'],
            ['role' => 'animales'],
        ]);
    }
}
