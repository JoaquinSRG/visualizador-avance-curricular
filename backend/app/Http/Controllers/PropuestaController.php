<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use App\Services\MotorPrerrequisitos;
use Illuminate\Http\Request;

class PropuestaController extends Controller
{
    /**
     * HU-15: reporte de propuesta de inscripción. Toma las materias que el
     * alumno ya puede cursar, empezando por las de semestres más bajos, hasta
     * el número de materias que elija.
     */
    public function show(Request $request, MotorPrerrequisitos $motor)
    {
        $datos = $request->validate([
            'max_materias' => ['nullable', 'integer', 'between:1,8'],
        ], [
            'max_materias.integer' => 'El número de materias debe ser un número entero.',
            'max_materias.between' => 'Puedes elegir de 1 a 8 materias.',
        ]);

        $maximo = (int) ($datos['max_materias'] ?? 6);
        $user = $request->user();

        $cursadas = $user->materiasCursadas()
            ->pluck('materias.id')
            ->all();

        $disponibles = Materia::with('prerrequisitos')
            ->orderBy('semestre')
            ->orderBy('clave')
            ->get()
            ->filter(fn (Materia $materia) => $motor->estado($materia, $cursadas)['estado'] === MotorPrerrequisitos::DISPONIBLE)
            ->values();

        $propuesta = $disponibles->take($maximo)->map(fn (Materia $materia) => [
            'id' => $materia->id,
            'clave' => $materia->clave,
            'nombre' => $materia->nombre,
            'semestre' => $materia->semestre,
            'creditos' => $materia->creditos,
        ])->values();

        return response()->json([
            'alumno' => $user->name,
            'fecha' => now()->toDateString(),
            'max_materias' => $maximo,
            'total_disponibles' => $disponibles->count(),
            'total_creditos' => (int) $propuesta->sum('creditos'),
            'materias' => $propuesta,
        ]);
    }
}
