<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabela livros
        Schema::create('livros', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('titulo', 255);
            $table->string('autor', 255);
            $table->string('isbn', 17)->unique();
            $table->integer('quantidade_copias')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('livros');
    }
};
