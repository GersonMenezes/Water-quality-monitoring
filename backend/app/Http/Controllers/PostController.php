<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post; // Importamos o modelo do nosso Banco de Dados

class PostController extends Controller
{
    // A classe Request captura tudo o que vem do Postman (o pacote HTTP)
    public function store(Request $request) 
    {
        // 1. Validação (Filtro de ruído)
        // Garante que não vamos salvar no banco dados vazios ou corrompidos
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // 2. Criação (Salvando no PostgreSQL)
        $post = Post::create([
            'user_id' => $request->user()->id, // Captura automática do ID do usuário autenticado
            'title' => $request->title,
            'content' => $request->content,
        ]);

        // 3. Resposta
        // Retornamos um JSON confirmando o sucesso e o código HTTP 201 (Created)
        return response()->json([
            'message' => 'Dado de sensor / Post recebido e salvo com sucesso!',
            'data' => $post
        ], 201);
    }

    // 1. LER TODOS (GET /api/posts)
    public function index()
    {
        // Busca todos os registros do PostgreSQL do mais recente para o mais antigo
        $posts = Post::latest()->get();
        return response()->json($posts, 200);
    }

    // 2. LER INDIVIDUAL (GET /api/posts/{id})
    public function show($id)
    {
        // Procura pelo ID. Se não encontrar, gera automaticamente um erro 404 (Not Found)
        $post = Post::findOrFail($id);
        return response()->json($post, 200);
    }

    // 3. ATUALIZAR (PUT /api/posts/{id})
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        // Valida as alterações
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // Aplica os novos dados
        $post->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return response()->json([
            'message' => 'Registro atualizado com sucesso!',
            'data' => $post
        ], 200);
    }

    // 4. DELETAR (DELETE /api/posts/{id})
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        
        // Remove fisicamente do PostgreSQL
        $post->delete();

        return response()->json([
            'message' => 'Registro removido com sucesso!'
        ], 200);
    }
}