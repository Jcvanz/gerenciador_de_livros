<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Livro;
use App\Models\Emprestimo;
use Laravel\Sanctum\Sanctum;
use App\Actions\EmprestarLivroAction;
use Exception;

class EmprestimoTest extends TestCase
{
    // Limpa o banco depois do teste
    use RefreshDatabase;

    // Teste de concorrência e esgotamento de cópias
    public function test_concorrencia_emprestimo_mesmo_livro()
    {
        $livro = Livro::factory()->create(['quantidade_copias' => 1]);
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $action = new EmprestarLivroAction();

        // Usuário 1 pega a única cópia
        $emprestimo1 = $action->execute($user1->id, $livro->id);
        $this->assertNotNull($emprestimo1);

        // Usuário 2 tenta pegar o mesmo livro
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Não há cópias disponíveis deste livro para empréstimo");
        
        $action->execute($user2->id, $livro->id);
    }

    // Teste de limite de empréstimos do usuário
    public function test_limite_emprestimos_usuario()
    {
        $user = User::factory()->create();
        $livros = Livro::factory()->count(4)->create(['quantidade_copias' => 2]);

        Sanctum::actingAs($user);

        // Pega 3 livros emprestados
        for ($i = 0; $i < 3; $i++) {
            $res = $this->postJson('/api/emprestar', [
                'livro_id' => $livros[$i]->id
            ]);
            $res->assertStatus(201);
        }

        // Tentativa de pegar o quarto livro
        $res = $this->postJson('/api/emprestar', [
            'livro_id' => $livros[3]->id
        ]);

        $res->assertStatus(400);
        $res->assertJsonFragment([
            'message' => 'Você atingiu o limite de livros emprestados'
        ]);
    }

    // Teste para verificar o fluxo de devolução
    public function test_devolucao_livro()
    {
        $user = User::factory()->create();
        $livro = Livro::factory()->create(['quantidade_copias' => 1]);

        Sanctum::actingAs($user);

        // Faz o empréstimo
        $res = $this->postJson('/api/emprestar', [
            'livro_id' => $livro->id
        ]);
        $res->assertStatus(201);
        $emprestimoId = $res->json('data.id');

        // Confirma estoque em zero
        $this->assertEquals(0, Livro::find($livro->id, ['*'])->quantidade_copias);

        // Devolve o livro
        $devolveRes = $this->postJson("/api/devolver/{$emprestimoId}");
        $devolveRes->assertStatus(200);

        // Confirma status atualizado e estoque restaurado
        $this->assertEquals(1, Livro::find($livro->id, ['*'])->quantidade_copias);
        $this->assertEquals('devolvido', Emprestimo::find($emprestimoId, ['*'])->status);
    }
}
