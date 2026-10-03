<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use Illuminate\Http\Request;

class MateriaController extends Controller
{
    public function index(Request $request)
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

        $materias->each(function (Materia $materia) use ($cursadas) {
            $materia->setAttribute('cursada', in_array($materia->id, $cursadas));
        });

        return response()->json($materias);
    }
}
