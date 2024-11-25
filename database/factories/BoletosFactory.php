<?php

namespace Database\Factories;

use App\Models\Boletos;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class BoletosFactory extends Factory
{
    protected $model = Boletos::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titulo' => $this->faker->sentence(),
            'descripcion' => $this->faker->text(45),
            'precio' => $this->faker->numberBetween(100,200),
            'imagen' => $this->faker->imageUrl(),
            'estado' => $this->faker->boolean(50)
        ];
    }
}
