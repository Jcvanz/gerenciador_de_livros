<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Livro;

class LivroSeeder extends Seeder
{
    // Seeder de teste dos livros
    public function run(): void
    {
        $livros = [
            [
                'titulo' => 'Dom Casmurro',
                'autor' => 'Machado de Assis',
                'isbn' => '978-8508172818',
                'quantidade_copias' => 4,
            ],
            [
                'titulo' => 'O Senhor dos Anéis: A Sociedade do Anel',
                'autor' => 'J. R. R. Tolkien',
                'isbn' => '978-8595084759',
                'quantidade_copias' => 3,
            ],
            [
                'titulo' => '1984',
                'autor' => 'George Orwell',
                'isbn' => '978-8535914849',
                'quantidade_copias' => 6,
            ],
            [
                'titulo' => 'Grande Sertão: Veredas',
                'autor' => 'João Guimarães Rosa',
                'isbn' => '978-8520941911',
                'quantidade_copias' => 1,
            ],
            [
                'titulo' => 'O Cortiço',
                'autor' => 'Aluísio Azevedo',
                'isbn' => '978-8508130252',
                'quantidade_copias' => 5,
            ],
            [
                'titulo' => 'Admirável Mundo Novo',
                'autor' => 'Aldous Huxley',
                'isbn' => '978-8525060242',
                'quantidade_copias' => 2,
            ],
            [
                'titulo' => 'A Hora da Estrela',
                'autor' => 'Clarice Lispector',
                'isbn' => '978-8532520623',
                'quantidade_copias' => 8,
            ],
            [
                'titulo' => 'O Pequeno Príncipe',
                'autor' => 'Antoine de Saint-Exupéry',
                'isbn' => '978-8522031436',
                'quantidade_copias' => 10,
            ],
            [
                'titulo' => 'Misto-Quente',
                'autor' => 'Charles Bukowski',
                'isbn' => '978-8525413987',
                'quantidade_copias' => 0, 
            ],
            [
                'titulo' => 'Memórias Póstumas de Brás Cubas',
                'autor' => 'Machado de Assis',
                'isbn' => '978-8535921823',
                'quantidade_copias' => 3,
            ],
            [
                'titulo' => 'Vidas Secas',
                'autor' => 'Graciliano Ramos',
                'isbn' => '978-8501114785',
                'quantidade_copias' => 2,
            ],
        ];

        foreach ($livros as $livro) {
            Livro::create($livro);
        }
    }
}
