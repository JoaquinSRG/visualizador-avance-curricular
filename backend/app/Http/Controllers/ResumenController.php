<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use App\Services\MotorPrerrequisitos;
use Illuminate\Http\Request;

class ResumenController extends Controller
{
    /**
     * HU-10: resumen del avance del estudiante.
     */
    public function show(Request $request, MotorPrerrequisitos $motor)
    {
        $cursadas = $request->user()
            ->materiasCursadas()
            ->pluck('materias.id')
            ->all();

        $materias = Materia::with('prerrequisitos:id,clave,nombre')
            ->orderBy('semestre')
            ->orderBy('clave')
            ->get();

        $conteo = [
            MotorPrerrequisitos::CURSADA => 0,
            MotorPrerrequisitos::DISPONIBLE => 0,
            MotorPrerrequisitos::BLOQUEADA => 0,
        ];
        $creditosCursados = 0;
        $paraInscribir = [];

        foreach ($materias as $materia) {
            $estado = $motor->estado($materia, $cursadas)['estado'];
            $conteo[$estado]++;

            if ($estado === MotorPrerrequisitos::CURSADA) {
                $creditosCursados += $materia->creditos;
            }

            if ($estado === MotorPrerrequisitos::DISPONIBLE) {
                $paraInscribir[] = $materia->only(['id', 'clave', 'nombre', 'semestre']);
            }
        }

        $total = $materias->count();

        return response()->json([
            'total_materias' => $total,
            'cursadas' => $conteo[MotorPrerrequisitos::CURSADA],
            'disponibles' => $conteo[MotorPrerrequisitos::DISPONIBLE],
            'bloqueadas' => $conteo[MotorPrerrequisitos::BLOQUEADA],
            'porcentaje' => $total > 0 ? (int) round($conteo[MotorPrerrequisitos::CURSADA] * 100 / $total) : 0,
            'creditos_cursados' => $creditosCursados,
            'creditos_totales' => (int) $materias->sum('creditos'),
            'para_inscribir' => $paraInscribir,
        ]);
    }
}
