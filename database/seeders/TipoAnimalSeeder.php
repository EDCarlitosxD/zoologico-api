<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoAnimalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('tipo_animales')->insert([
            ['nombre' => 'Mamífero'],
            ['nombre' => 'Ave'],
            ['nombre' => 'Reptil'],
            ['nombre' => 'Pez'],
            ['nombre' => 'Anfibio']
        ]);
    }
}
