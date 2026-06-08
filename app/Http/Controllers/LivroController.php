<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreLivroRequest;
use App\Models\Livro;

class LivroController extends Controller
{
    public function index()
    {
        // Retorna todos os livros
        $livro = Livro::all();
        return response()->json($livro);
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
