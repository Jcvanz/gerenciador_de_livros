<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreLivroRequest;
use App\Models\Livro;

class LivroController extends Controller
{
    public function index()
    {
        // Retorna todos os livros (10 por página)
        $livros = Livro::paginate(10);
        return response()->json($livros);
    }

    public function store(StoreLivroRequest $request)
    {
        // Cadastra um novo livro
        $livro = Livro::create($request->validated());

        return response()->json([
            'message' => 'Livro cadastrado',
            'data' => $livro
        ], 201);
    }
}
