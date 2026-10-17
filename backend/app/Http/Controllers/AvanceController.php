<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use App\Services\MotorPrerrequisitos;
use Illuminate\Http\Request;

class AvanceController extends Controller
{
    /**
     * Marca una materia como cursada para el usuario de la sesión.
     * HU-05: no se permite si le faltan prerrequisitos.
     */
    public function store(Request $request, Materia $materia, MotorPrerrequisitos $motor)
    {
        $user = $request->user();
        $cursadas = $user->materiasCursadas()->pluck('materias.id')->all();

        $faltantes = $motor->faltantes($materia->load('prerrequisitos'), $cursadas);

        if ($faltantes->isNotEmpty()) {
            return response()->json([
                'message' => "No puedes marcar {$materia->nombre} porque te falta: "
                    .$faltantes->pluck('nombre')->join(', ', ' y ').'.',
                'faltantes' => $faltantes,
            ], 422);
        }

        $user->materiasCursadas()->syncWithoutDetaching([$materia->id]);

        return response()->json([
            'materia_id' => $materia->id,
            'cursada' => true,
        ]);
    }

    /**
     * Quita la marca de cursada.
     * HU-05: no se permite si otra materia cursada depende de esta.
     */
    public function destroy(Request $request, Materia $materia, MotorPrerrequisitos $motor)
    {
        $user = $request->user();
        $cursadas = $user->materiasCursadas()->pluck('materias.id')->all();

        $dependientes = $motor->dependientesCursadas($materia->load('esPrerrequisitoDe'), $cursadas);

        if ($dependientes->isNotEmpty()) {
            return response()->json([
                'message' => "No puedes desmarcar {$materia->nombre} porque ya marcaste: "
                    .$dependientes->pluck('nombre')->join(', ', ' y ').'. Desmarca primero esas materias.',
            ], 422);
        }

        $user->materiasCursadas()->detach($materia->id);

        return response()->json([
            'materia_id' => $materia->id,
            'cursada' => false,
        ]);
    }
}
