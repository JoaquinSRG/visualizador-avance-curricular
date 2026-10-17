<?php

namespace App\Services;

use App\Models\Materia;
use Illuminate\Support\Collection;

/**
 * Motor de validación de prerrequisitos (HU-05).
 *
 * Decide el estado de cada materia según lo que el estudiante ya cursó.
 * Todas las reglas leen los prerrequisitos de la base de datos, así que si
 * cambia la seriación solo hay que corregir el seeder y no este código.
 */
class MotorPrerrequisitos
{
    public const CURSADA = 'cursada';

    public const DISPONIBLE = 'disponible';

    public const BLOQUEADA = 'bloqueada';

    /**
     * Regresa el estado de una materia y los prerrequisitos que le faltan.
     *
     * @param  array<int>  $cursadas  Ids de las materias que el estudiante ya cursó
     * @return array{estado: string, faltantes: array<int, array{id: int, clave: string, nombre: string}>}
     */
    public function estado(Materia $materia, array $cursadas): array
    {
        if (in_array($materia->id, $cursadas)) {
            return ['estado' => self::CURSADA, 'faltantes' => []];
        }

        $faltantes = $this->faltantes($materia, $cursadas);

        return [
            'estado' => $faltantes->isEmpty() ? self::DISPONIBLE : self::BLOQUEADA,
            'faltantes' => $faltantes->all(),
        ];
    }

    /**
     * Prerrequisitos de la materia que el estudiante todavía no cursa.
     *
     * @param  array<int>  $cursadas
     */
    public function faltantes(Materia $materia, array $cursadas): Collection
    {
        return $materia->prerrequisitos
            ->reject(fn (Materia $prerrequisito) => in_array($prerrequisito->id, $cursadas))
            ->map(fn (Materia $prerrequisito) => [
                'id' => $prerrequisito->id,
                'clave' => $prerrequisito->clave,
                'nombre' => $prerrequisito->nombre,
            ])
            ->values();
    }

    /**
     * Materias ya cursadas que dependen de esta. Si hay alguna, no se puede
     * desmarcar la materia sin dejar el avance incongruente.
     *
     * @param  array<int>  $cursadas
     */
    public function dependientesCursadas(Materia $materia, array $cursadas): Collection
    {
        return $materia->esPrerrequisitoDe
            ->filter(fn (Materia $dependiente) => in_array($dependiente->id, $cursadas))
            ->values();
    }
}
