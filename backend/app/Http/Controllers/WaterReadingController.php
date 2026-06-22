<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WaterReading;

class WaterReadingController extends Controller
{
    public function store(Request $request){
        // 1. Validação (Filtro de ruído)
        // Garante que não vamos salvar no banco dados vazios ou corrompidos
        $request->validate([
            'ph_level' => 'required|numeric|min:0|max:14',
            'temperature' => 'required|numeric|min:-20|max:100',
        ]);

        $status = 'normal';
        if ($request->ph_level < 6.5 || $request->ph_level > 8.5 || $request->temperature > 35) {            
            $status = 'alert';
        }

        // 2. Criação (Salvando no PostgreSQL)
        $waterReading = WaterReading::create([
            'user_id' => $request->user()->id,
            'ph_level' => $request->ph_level,
            'temperature' => $request->temperature,
            'status' => $status, // Gravado dinamicamente
        ]);

        // 3. Resposta
        // Retornamos um JSON confirmando o sucesso e o código HTTP 201 (Created)

        return response()->json([
            'message' => 'Leitura de sensor recebida e salva com sucesso!',
            'data' => $waterReading
        ], 201);
    }

    public function index(){
        $waterReadings = WaterReading::latest()->get();
        return response()->json($waterReadings, 200);
    }

    public function show($id){
        $waterReading = WaterReading::findOrFail($id);
        return response()->json($waterReading, 200);
    }

    public function update(Request $request, $id){
        $waterReading = WaterReading::findOrFail($id);

        $request->validate([
            'ph_level' => 'required|numeric|min:0|max:14',
            'temperature' => 'required|numeric|min:-20|max:100',
        ]);

        $waterReading->update($request->all());

        return response()->json([
            'message' => 'Leitura de sensor atualizada com sucesso!',
            'data' => $waterReading
        ], 200);
    }

    public function destroy($id){
        $waterReading = WaterReading::findOrFail($id);
        $waterReading->delete();

        // 4. Resposta
        // Retornamos um JSON confirmando o sucesso e o código HTTP 200 (OK)
        return response()->json([
            'message' => 'Leitura de sensor removida com sucesso!',
        ], 200);
        }
}