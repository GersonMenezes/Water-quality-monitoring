<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WaterReadingController;

// ----------------------------------------------------------------
// ROTAS PÚBLICAS (Qualquer um acessa sem token)
// ----------------------------------------------------------------
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/readings', [WaterReadingController::class, 'index']);
// ----------------------------------------------------------------
// ROTAS PROTEGIDAS (Exigem o cabeçalho 'Authorization: Bearer <token>')
// ----------------------------------------------------------------
    Route::middleware('auth:sanctum')->group(function () {
        
        // Rota de Logout
        Route::post('/logout', [AuthController::class, 'logout']);

        // Todo o nosso CRUD de posts agora está seguro aqui dentro!
        Route::post('/posts', [PostController::class, 'store']);
        Route::get('/posts', [PostController::class, 'index']);
        Route::get('/posts/{id}', [PostController::class, 'show']);
        Route::put('/posts/{id}', [PostController::class, 'update']);
        Route::delete('/posts/{id}', [PostController::class, 'destroy']);

        // Todo o nosso CRUD de leituras de sensor agora está seguro aqui dentro!
        Route::post('/readings', [WaterReadingController::class, 'store']);
        
        Route::get('/readings/{id}', [WaterReadingController::class, 'show']);
        Route::put('/readings/{id}', [WaterReadingController::class, 'update']);
        Route::delete('/readings/{id}', [WaterReadingController::class, 'destroy']);
    }
);