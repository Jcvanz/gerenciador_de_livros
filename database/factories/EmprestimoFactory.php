<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Emprestimo;
use App\Models\User;
use App\Models\Livro;
use Carbon\Carbon;

class EmprestimoFactory extends Factory
{
    protected $model = Emprestimo::class;
    
    // Factory dos empréstimos de teste
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'livro_id' => Livro::factory(),
            'data_emprestimo' => Carbon::now(),
            'data_devolucao' => null,
            'status' => 'ativo',
        ];
    }
}
