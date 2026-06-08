<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Livro;

class LivroFactory extends Factory
{
    protected $model = Livro::class;

    // Factory dos livros de teste
    public function definition(): array
    {
        return [
            'titulo' => $this->faker->sentence(3),
            'autor' => $this->faker->name,
            'isbn' => $this->faker->unique()->numerify('###-##########'),
            'quantidade_copias' => 5
        ];
    }
}
