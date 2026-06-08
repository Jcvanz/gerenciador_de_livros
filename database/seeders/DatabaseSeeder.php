<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Cria usuário padrão para testes
        User::factory()->create([
            'name' => 'Teste Biblioteca',
            'email' => 'teste@biblioteca.com',
            'password' => bcrypt('senha123'),
        ]);

        // Chamando o seeder de livros
        $this->call([
            LivroSeeder::class,
        ]);
    }
}
