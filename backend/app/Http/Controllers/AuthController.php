<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // 1. REGISTRO DE NOVO USUÁRIO
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed' // exige o campo password_confirmation
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password) // Criptografia da senha
        ]);

        // Cria o token de acesso para o usuário recém-criado
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Usuário registrado com sucesso!',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    // 2. LOGIN E GERAÇÃO DE TOKEN
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Procura o usuário pelo e-mail
        $user = User::where('email', $request->email)->first();

        // Verifica se o usuário existe e se a senha está correta
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Credenciais inválidas.'
            ], 401);
        }

        // Gera um novo token para esta sessão
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login realizado com sucesso!',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }

    // 3. LOGOUT (DESTRUIR TOKEN)
    public function logout(Request $request)
    {
        // Apaga o token atual que foi usado para autenticar esta requisição
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout realizado. Token revogado com sucesso!'
        ], 200);
    }
}
