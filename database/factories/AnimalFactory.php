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
            'imagen_principal' => $this->faker->imageUrl(640, 480, 'animals', true),
            'imagen_secundaria' => $this->faker->imageUrl(640, 480, 'animals', true),
            'caracteristicas_fisicas' => $this->faker->sentence(15),
            'dieta' => $this->faker->sentence(10),
            'datos_curiosos' => $this->faker->paragraph(3),
            'comportamiento' => $this->faker->paragraph(4),
            // 'informacion' => $this->faker->paragraph(5),
            'habitat' => $this->faker->paragraph(2),
            'peso' => $this->faker->randomFloat(2, 0.1, 500), // Peso en kilogramos (0.1kg - 500kg)
            'altura' => $this->faker->randomFloat(2, 0.1, 10), // Altura en metros (0.1m - 10m)
            'tipo' => $this->faker->randomElement(['acuático', 'terrestre', 'aéreo', 'anfibio']),
            'descripcion' => $this->faker->paragraph(4),
            'subtitulo' => $this->faker->paragraph(1),
            'estado' => true,
            'qr' => '',
            'img_ubicacion' => $this->faker->imageUrl()
            // 'tipo_animal_id' => random_int(1,5),

        ];
    }
}
