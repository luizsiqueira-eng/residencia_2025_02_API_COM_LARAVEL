<?php

namespace Database\Factories;

use App\Models\Cliente;
use App\Models\Veiculo;
use Illuminate\Database\Eloquent\Factories\Factory;

class VeiculoFactory extends Factory
{
    protected $model = Veiculo::class;

    public function definition()
    {
        return [
            'cliente_id' => Cliente::factory(),
            'placa' => strtoupper($this->faker->unique()->bothify('???#?##')),
            'marca' => $this->faker->randomElement(['Fiat', 'Volkswagen', 'Chevrolet', 'Toyota', 'Hyundai']),
            'modelo' => $this->faker->randomElement(['Argo', 'Polo', 'Onix', 'Corolla', 'HB20']),
            'cor' => $this->faker->safeColorName(),
        ];
    }
}
