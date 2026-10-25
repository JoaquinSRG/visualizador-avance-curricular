<?php

namespace Tests\Feature;

use App\Models\Materia;
use App\Models\User;
use Database\Seeders\MateriaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * HU-07: la aplicación funciona en distintos escenarios de avance:
 * estudiante nuevo, estudiante a la mitad de la carrera y carrera completa.
 */
class EscenariosTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(MateriaSeeder::class);
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    public function test_estudiante_nuevo_solo_tiene_disponibles_las_materias_sin_prerrequisitos(): void
    {
        $materias = $this->getJson('/api/materias')->assertOk()->json();

        foreach ($materias as $materia) {
            $esperado = count($materia['prerrequisitos']) === 0 ? 'disponible' : 'bloqueada';

            $this->assertSame($esperado, $materia['estado'], "Estado incorrecto en {$materia['clave']}");
            $this->assertFalse($materia['cursada']);
        }

        $this->getJson('/api/resumen')->assertOk()->assertJson([
            'cursadas' => 0,
            'porcentaje' => 0,
            'creditos_cursados' => 0,
        ]);
    }

    public function test_estudiante_a_la_mitad_de_la_carrera_tiene_estados_congruentes(): void
    {
        // Cursó los primeros cuatro semestres completos
        $cursadas = Materia::where('semestre', '<=', 4)->get();
        $this->user->materiasCursadas()->attach($cursadas->pluck('id'));

        $materias = $this->getJson('/api/materias')->assertOk()->json();
        $idsCursadas = $cursadas->pluck('id')->all();

        foreach ($materias as $materia) {
            if ($materia['semestre'] <= 4) {
                $this->assertSame('cursada', $materia['estado'], "{$materia['clave']} debería estar cursada");
                continue;
            }

            // Una materia está disponible solo si todos sus prerrequisitos ya se cursaron
            $faltan = collect($materia['prerrequisitos'])
                ->reject(fn ($prerrequisito) => in_array($prerrequisito['id'], $idsCursadas));
            $esperado = $faltan->isEmpty() ? 'disponible' : 'bloqueada';

            $this->assertSame($esperado, $materia['estado'], "Estado incorrecto en {$materia['clave']}");
        }

        $resumen = $this->getJson('/api/resumen')->assertOk();
        $total = Materia::count();

        $resumen->assertJson([
            'cursadas' => $cursadas->count(),
            'porcentaje' => (int) round($cursadas->count() * 100 / $total),
            'creditos_cursados' => (int) $cursadas->sum('creditos'),
        ]);
        $this->assertGreaterThan(0, $resumen->json('disponibles'));
        $this->assertGreaterThan(0, $resumen->json('bloqueadas'));
        $this->assertSame(
            $total,
            $resumen->json('cursadas') + $resumen->json('disponibles') + $resumen->json('bloqueadas'),
        );
    }

    public function test_carrera_completa_no_deja_materias_disponibles_ni_bloqueadas(): void
    {
        $this->user->materiasCursadas()->attach(Materia::pluck('id'));

        $materias = $this->getJson('/api/materias')->assertOk()->json();

        foreach ($materias as $materia) {
            $this->assertSame('cursada', $materia['estado'], "{$materia['clave']} debería estar cursada");
            $this->assertSame([], $materia['faltantes']);
        }

        $resumen = $this->getJson('/api/resumen')->assertOk();

        $resumen->assertJson([
            'total_materias' => 44,
            'cursadas' => 44,
            'disponibles' => 0,
            'bloqueadas' => 0,
            'porcentaje' => 100,
            'para_inscribir' => [],
        ]);
        $this->assertSame($resumen->json('creditos_totales'), $resumen->json('creditos_cursados'));
    }

    public function test_se_puede_completar_la_carrera_marcando_materia_por_materia(): void
    {
        // Se marcan en orden de semestre: cada una debe aceptarse porque
        // sus prerrequisitos ya se marcaron antes
        $materias = Materia::orderBy('semestre')->orderBy('clave')->get();

        foreach ($materias as $materia) {
            $this->postJson("/api/materias/{$materia->id}/cursada")->assertSuccessful();
        }

        $this->getJson('/api/resumen')->assertOk()->assertJson([
            'cursadas' => 44,
            'porcentaje' => 100,
        ]);
    }

    public function test_al_desmarcar_toda_la_carrera_se_regresa_al_escenario_inicial(): void
    {
        $this->user->materiasCursadas()->attach(Materia::pluck('id'));

        // Se desmarcan de la última materia a la primera para respetar las dependencias
        $materias = Materia::orderByDesc('semestre')->orderByDesc('clave')->get();

        foreach ($materias as $materia) {
            $this->deleteJson("/api/materias/{$materia->id}/cursada")->assertSuccessful();
        }

        $this->getJson('/api/resumen')->assertOk()->assertJson([
            'cursadas' => 0,
            'porcentaje' => 0,
        ]);
    }
}
