<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Tabela emprestimos
    public function up(): void
    {
        Schema::create('emprestimos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('data_emprestimo');
            $table->dateTime('data_devolucao')->nullable();
            $table->enum('status', ['ativo', 'devolvido', 'atrasado']);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('livro_id')->constrained('livros')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emprestimos');
    }
};
