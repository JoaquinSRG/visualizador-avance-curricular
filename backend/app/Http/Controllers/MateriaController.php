<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use App\Services\MotorPrerrequisitos;
use Illuminate\Http\Request;

class MateriaController extends Controller
{
    public function index(Request $request, MotorPrerrequisitos $motor)
    {
        // Ids de las materias que el usuario ya marcó como cursadas
        $cursadas = $request->user()
            ->materiasCursadas()
            ->pluck('materias.id')
            ->all();

        $materias = Materia::with('prerrequisitos:id,clave,nombre')
            ->orderBy('semestre')
            ->orderBy('clave')
            ->get(['id', 'clave', 'nombre', 'semestre', 'creditos']);

        // HU-05: cada materia regresa si está cursada, disponible o bloqueada
        $materias->each(function (Materia $materia) use ($cursadas, $motor) {
            $resultado = $motor->estado($materia, $cursadas);

            $materia->setAttribute('cursada', $resultado['estado'] === MotorPrerrequisitos::CURSADA);
            $materia->setAttribute('estado', $resultado['estado']);
            $materia->setAttribute('faltantes', $resultado['faltantes']);
        });

        return response()->json($materias);
    }
}
