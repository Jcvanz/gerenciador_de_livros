<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Actions\EmprestarLivroAction;
use App\Actions\DevolverLivroAction;
use App\Http\Requests\EmprestarLivroRequest;
use Exception;

class EmprestimoController extends Controller
{
    // Lista os livros emprestados pelo usuário
    public function meusEmprestimos(Request $request)
    {
        $userId = $request->user()->id;
        $meusEmprestimos = DB::select("
            SELECT 
                em.id, em.data_emprestimo, em.data_devolucao, em.status, 
                li.titulo, li.autor, li.isbn
            FROM emprestimos em
                JOIN livros li ON em.livro_id = li.id
                WHERE em.user_id = ?
            ORDER BY em.data_emprestimo DESC
        ", [$userId]);

        return response()->json($meusEmprestimos);
    }

    // Função para emprestar livros
    public function emprestar(EmprestarLivroRequest $request, EmprestarLivroAction $action)
    {
        try {
            $userId = $request->user()->id;
            $livroId = $request->validated()['livro_id'];

            $emprestimo = $action->execute($userId, $livroId);

            return response()->json([
                'message' => 'Livro emprestado',
                'data' => $emprestimo
            ]);

        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    // Função para devolver livros
    public function devolver(Request $request, $id, DevolverLivroAction $action)
    {
        try {
            $userId = $request->user()->id;
            $emprestimo = $action->execute($userId, (int) $id);

            return response()->json([
                'message' => 'Livro devolvido',
                'data' => $emprestimo
            ]);

        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
