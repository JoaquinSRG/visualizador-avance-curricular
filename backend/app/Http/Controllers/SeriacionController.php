<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use App\Services\MotorPrerrequisitos;
use Illuminate\Http\Request;

class SeriacionController extends Controller
{
    /**
     * HU-14: requisitos para cursar una materia y materias seriadas.
     * Regresa qué materias pide (y si ya las cursó el alumno) y qué materias
     * se desbloquean con ella.
     */
    public function show(Request $request, Materia $materia, MotorPrerrequisitos $motor)
    {
        $cursadas = $request->user()
            ->materiasCursadas()
            ->pluck('materias.id')
            ->all();

        $materia->load(['prerrequisitos.prerrequisitos', 'esPrerrequisitoDe.prerrequisitos']);

        $requisitos = $materia->prerrequisitos->map(fn (Materia $requisito) => [
            'id' => $requisito->id,
            'clave' => $requisito->clave,
            'nombre' => $requisito->nombre,
            'semestre' => $requisito->semestre,
            'cumplido' => in_array($requisito->id, $cursadas),
        ])->values();

        $desbloquea = $materia->esPrerrequisitoDe
            ->sortBy('semestre')
            ->map(fn (Materia $seriada) => [
                'id' => $seriada->id,
                'clave' => $seriada->clave,
                'nombre' => $seriada->nombre,
                'semestre' => $seriada->semestre,
                'estado' => $motor->estado($seriada, $cursadas)['estado'],
            ])->values();

        return response()->json([
            'id' => $materia->id,
            'clave' => $materia->clave,
            'nombre' => $materia->nombre,
            'semestre' => $materia->semestre,
            'creditos' => $materia->creditos,
            'estado' => $motor->estado($materia, $cursadas)['estado'],
            'requisitos' => $requisitos,
            'desbloquea' => $desbloquea,
        ]);
    }
}
