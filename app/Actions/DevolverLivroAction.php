<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;
use App\Models\Emprestimo;
use Carbon\Carbon;
use Exception;

class DevolverLivroAction
{
    public function execute(int $userId, int $emprestimoId): Emprestimo
    {
        return DB::transaction(function () use ($userId, $emprestimoId) {
            
            // Busca o registro do empréstimo
            $emprestimo = Emprestimo::find($emprestimoId);

            if (!$emprestimo) {
                throw new Exception("Nenhum registro de empréstimo encontrado");
            }

            // Garante que o empréstimo pertence ao usuário
            if ($emprestimo->user_id !== $userId) {
                throw new Exception("Não é possível devolver este livro");
            }

            if ($emprestimo->status === 'devolvido') {
                throw new Exception("Este livro já foi devolvido");
            }

            // Atualiza status e data de devolução
            DB::update(
                "UPDATE emprestimos 
                    SET status = 'devolvido', data_devolucao = ?, updated_at = ? 
                    WHERE id = ?", 
                [Carbon::now(), Carbon::now(), $emprestimoId]
            );

            // Aumenta a quantidade de cópias do livro
            DB::update(
                "UPDATE livros 
                    SET quantidade_copias = quantidade_copias + 1, updated_at = ? 
                    WHERE id = ?", 
                [Carbon::now(), $emprestimo->livro_id]
            );

            $emprestimo->refresh();
            return $emprestimo;
        });
    }
}
