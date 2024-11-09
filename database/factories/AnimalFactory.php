<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Animal>
 */
class AnimalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nombre = $this->faker->unique()->name();
        return [
            'nombre' => $nombre,
            'nombre_cientifico' => $this->faker->unique()->words(2,true),
            'slug' => Str::slug($nombre),
            'caracteristicas_fisicas' => $this->faker->sentence(15),
            'dieta' => $this->faker->sentence(10),
            'datos_curiosos' => $this->faker->paragraph(3),
            'comportamiento' => $this->faker->paragraph(4),
            'informacion' => $this->faker->paragraph(5),
            'imagen_principal' => $this->faker->imageUrl(),
            'imagen_secundaria' => $this->faker->imageUrl(),
            'activo' => $this->faker->boolean,
            'tipo_animal_id' => random_int(1,5), 
        ];
    }
}
