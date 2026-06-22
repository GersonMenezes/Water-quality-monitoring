<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class PostApiTest extends TestCase
{
    // Esta linha mágica limpa o banco de memória RAM toda vez que um teste começa
    use RefreshDatabase;

    #[Test]
    public function it_blocks_unauthenticated_users_from_creating_posts()
    {
        // 1. A Ação: Tentar fazer um POST na rota sem enviar nenhum Token
        $response = $this->postJson('/api/posts', [
            'title' => 'Tentativa de Hack',
            'content' => 'Conteúdo malicioso',
        ]);

        // 2. A Afirmação (Assert): Eu espero que o Laravel retorne o erro 401 (Não Autorizado)
        $response->assertStatus(401);
        
        // E eu espero que o banco de dados de posts continue vazio (count de 0)
        $this->assertCount(0, Post::all());
    }

    #[Test]
    public function it_allows_authenticated_users_to_create_posts()
    {
        // 1. Preparação: O robô cria um usuário falso na memória
        $user = User::factory()->create();

        // 2. Ação: O robô se autentica (actingAs) como esse usuário e envia o POST
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/posts', [
            'title' => 'Leitura de Sensor Válida',
            'content' => 'pH 7.0',
        ]);

        // 3. Afirmação: Eu espero que a resposta seja 201 (Criado)
        $response->assertStatus(201);

        // Eu espero que agora o banco tenha exatamente 1 post gravado
        $this->assertCount(1, Post::all());

        // Eu espero que o banco tenha salvo exatamente o título que eu enviei
        $this->assertDatabaseHas('posts', [
            'title' => 'Leitura de Sensor Válida',
        ]);
    }
}
