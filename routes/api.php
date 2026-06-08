<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LivroController;
use App\Http\Controllers\EmprestimoController;

// Rota padrão para pegar o usuário logado
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Grupo de rotas protegidas
Route::middleware('auth:sanctum')->group(function () {
    
    // Livros
    Route::get('/livros', [LivroController::class, 'index']);
    Route::post('/livros', [LivroController::class, 'store']); 
    
    // Empréstimos
    Route::get('/meus-emprestimos', [EmprestimoController::class, 'meusEmprestimos']);
    Route::post('/emprestar', [EmprestimoController::class, 'emprestar']);
    Route::post('/devolver/{id}', [EmprestimoController::class, 'devolver']);
});
