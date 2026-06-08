<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;
use App\Models\Emprestimo;
use Carbon\Carbon;
use Exception;

class EmprestarLivroAction
{
    public function execute(int $userId, int $livroId): Emprestimo
    {
        return DB::transaction(function () use ($userId, $livroId) {
            
            // Verifica se o livro existe e se tem cópias disponíveis
            $driverName = DB::connection()->getDriverName();
            $qtdeCopiasSql = "
                SELECT quantidade_copias 
                    FROM livros WHERE id = ?"
            ;
            
            if ($driverName !== 'sqlite') {
                $qtdeCopiasSql .= " FOR UPDATE";
            }
            
            $qtdeCopias = DB::select($qtdeCopiasSql, [$livroId]);

            if ($qtdeCopias[0]->quantidade_copias <= 0) {
                throw new Exception("Livro esgotado");
            }

            // Verifica se o usuario ja tem 3 livros emprestados
            $ativos = DB::select("
                SELECT COUNT(*) as total 
                    FROM emprestimos 
                    WHERE user_id = ? AND status = 'ativo'
            ", [$userId]);
            
            if ($ativos[0]->total >= 3) {
                throw new Exception("Limite de 3 livros atingido");
            }

            // Atualiza a quantidade de cópias no livro
            DB::update("
                UPDATE livros 
                    SET quantidade_copias = quantidade_copias - 1 
                    WHERE id = ?
            ", [$livroId]);

            // Cria registro em emprestimos
            $dataEmprestimo = Carbon::now();
            $idEmprestimo = DB::table('emprestimos')->insertGetId([
                'user_id' => $userId,
                'livro_id' => $livroId,
                'data_emprestimo' => $dataEmprestimo,
                'data_devolucao' => null,
                'status' => 'ativo',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            return Emprestimo::find($idEmprestimo);
        });
    }
}
