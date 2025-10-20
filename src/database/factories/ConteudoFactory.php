<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\ConteudoStatusEnum;

class ConteudoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * O estado padrão será um conteúdo recém-criado, com status 'escrito'.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'papel' => $this->faker->randomElement(['redator', 'marketing', 'revisor']),
            'conteudo' => $this->faker->realText(200), // Gera um texto real com 200 caracteres, garantindo a validação min:20
            'status' => ConteudoStatusEnum::ESCRITO,
            'motivo_reprovacao' => null, // Nulo por padrão
        ];
    }

    /**
     * Indica que o conteúdo está com status 'aprovado'.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function aprovado()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => ConteudoStatusEnum::APROVADO,
                'motivo_reprovacao' => null,
            ];
        });
    }

    /**
     * Indica que o conteúdo está com status 'reprovado'.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function reprovado()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => ConteudoStatusEnum::REPROVADO,
                'motivo_reprovacao' => $this->faker->realText(100), 
            ];
        });
    }
}
