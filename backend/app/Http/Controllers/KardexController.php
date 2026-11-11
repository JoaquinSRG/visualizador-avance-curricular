<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use Illuminate\Http\Request;

class KardexController extends Controller
{
    /**
     * HU-13: kárdex del alumno. Regresa las materias cursadas por semestre,
     * con su calificación, los créditos por semestre y el promedio.
     */
    public function show(Request $request)
    {
        $user = $request->user();

        $cursadas = $user->materiasCursadas()
            ->orderBy('materias.semestre')
            ->orderBy('materias.clave')
            ->get();

        $semestres = $cursadas
            ->groupBy('semestre')
            ->map(function ($materias, $semestre) {
                return [
                    'semestre' => (int) $semestre,
                    'creditos' => (int) $materias->sum('creditos'),
                    'promedio' => $this->promedio($materias),
                    'materias' => $materias->map(fn (Materia $materia) => [
                        'id' => $materia->id,
                        'clave' => $materia->clave,
                        'nombre' => $materia->nombre,
                        'creditos' => $materia->creditos,
                        'calificacion' => $materia->pivot->calificacion,
                    ])->values(),
                ];
            })
            ->values();

        $creditosCursados = (int) $cursadas->sum('creditos');
        $creditosTotales = (int) Materia::sum('creditos');

        return response()->json([
            'alumno' => $user->name,
            'materias_cursadas' => $cursadas->count(),
            'total_materias' => Materia::count(),
            'creditos_cursados' => $creditosCursados,
            'creditos_totales' => $creditosTotales,
            'creditos_faltantes' => $creditosTotales - $creditosCursados,
            'promedio_general' => $this->promedio($cursadas),
            'semestres' => $semestres,
        ]);
    }

    /**
     * HU-13: guarda o borra la calificación de una materia cursada.
     */
    public function calificar(Request $request, Materia $materia)
    {
        $datos = $request->validate([
            'calificacion' => ['nullable', 'integer', 'between:0,100'],
        ], [
            'calificacion.integer' => 'La calificación debe ser un número entero.',
            'calificacion.between' => 'La calificación debe estar entre 0 y 100.',
        ]);

        $user = $request->user();

        if (! $user->materiasCursadas()->where('materias.id', $materia->id)->exists()) {
            return response()->json([
                'message' => "Primero marca {$materia->nombre} como cursada.",
            ], 422);
        }

        $user->materiasCursadas()->updateExistingPivot($materia->id, [
            'calificacion' => $datos['calificacion'] ?? null,
        ]);

        return response()->json([
            'materia_id' => $materia->id,
            'calificacion' => $datos['calificacion'] ?? null,
        ]);
    }

    /**
     * Promedio de las materias que ya tienen calificación, con un decimal.
     * Si ninguna tiene calificación regresa null.
     */
    private function promedio($materias): ?float
    {
        $calificaciones = $materias
            ->pluck('pivot.calificacion')
            ->filter(fn ($calificacion) => $calificacion !== null);

        return $calificaciones->isEmpty() ? null : round($calificaciones->avg(), 1);
    }
}
