<?php

namespace App\Http\Controllers;

use App\Models\Materia;

class MateriaController extends Controller
{
    public function index()
    {
        $materias = Materia::with('Prerrequisitos:id,clave,nombre')
            ->orderBy('semestre')
            ->orderBy('clave')
            ->get(['id', 'clave', 'nombre', 'semestre', 'creditos']);

        return response()->json($materias);
    }
}
