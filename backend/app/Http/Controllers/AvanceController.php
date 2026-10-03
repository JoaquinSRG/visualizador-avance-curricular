<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use Illuminate\Http\Request;

class AvanceController extends Controller
{
    /**
     * Marca una materia como cursada para el usuario de la sesión.
     */
    public function store(Request $request, Materia $materia)
    {
        $request->user()->materiasCursadas()->syncWithoutDetaching([$materia->id]);

        return response()->json([
            'materia_id' => $materia->id,
            'cursada' => true,
        ]);
    }

    /**
     * Quita la marca de cursada.
     */
    public function destroy(Request $request, Materia $materia)
    {
        $request->user()->materiasCursadas()->detach($materia->id);

        return response()->json([
            'materia_id' => $materia->id,
            'cursada' => false,
        ]);
    }
}
