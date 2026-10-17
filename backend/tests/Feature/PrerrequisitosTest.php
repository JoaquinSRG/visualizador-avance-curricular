<?php

namespace Tests\Feature;

use App\Models\Materia;
use App\Models\User;
use Database\Seeders\MateriaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * HU-05: motor de validación de prerrequisitos.
 * Usa la cadena Proyecto I (LDSW105) -> Proyecto II (LDSW206) -> Proyecto III (LDSW306).
 */
class PrerrequisitosTest extends TestCase
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

    private function materia(string $clave): Materia
    {
        return Materia::where('clave', $clave)->firstOrFail();
    }

    /**
     * Busca una materia en la respuesta de GET /api/materias.
     */
    private function consultar(Materia $materia): array
    {
        return collect($this->getJson('/api/materias')->assertOk()->json())
            ->firstWhere('id', $materia->id);
    }

    public function test_una_materia_sin_prerrequisitos_esta_disponible(): void
    {
        $proyecto1 = $this->materia('LDSW105');

        $resultado = $this->consultar($proyecto1);

        $this->assertSame('disponible', $resultado['estado']);
        $this->assertSame([], $resultado['faltantes']);
    }

    public function test_una_materia_con_prerrequisitos_pendientes_esta_bloqueada(): void
    {
        $proyecto1 = $this->materia('LDSW105');
        $proyecto2 = $this->materia('LDSW206');

        $resultado = $this->consultar($proyecto2);

        $this->assertSame('bloqueada', $resultado['estado']);
        $this->assertSame($proyecto1->id, $resultado['faltantes'][0]['id']);
    }

    public function test_al_cursar_el_prerrequisito_la_materia_se_desbloquea(): void
    {
        $this->user->materiasCursadas()->attach($this->materia('LDSW105')->id);
        $proyecto2 = $this->materia('LDSW206');

        $this->assertSame('disponible', $this->consultar($proyecto2)['estado']);
    }

    public function test_una_materia_cursada_regresa_estado_cursada(): void
    {
        $proyecto1 = $this->materia('LDSW105');
        $this->user->materiasCursadas()->attach($proyecto1->id);

        $resultado = $this->consultar($proyecto1);

        $this->assertTrue($resultado['cursada']);
        $this->assertSame('cursada', $resultado['estado']);
    }

    public function test_no_se_puede_marcar_una_materia_bloqueada(): void
    {
        $proyecto2 = $this->materia('LDSW206');

        $this->postJson("/api/materias/{$proyecto2->id}/cursada")
            ->assertStatus(422)
            ->assertJsonPath('faltantes.0.clave', 'LDSW105');

        $this->assertDatabaseMissing('materias_cursadas', [
            'user_id' => $this->user->id,
            'materia_id' => $proyecto2->id,
        ]);
    }

    public function test_no_se_puede_desmarcar_un_prerrequisito_de_una_materia_cursada(): void
    {
        $proyecto1 = $this->materia('LDSW105');
        $proyecto2 = $this->materia('LDSW206');
        $this->user->materiasCursadas()->attach([$proyecto1->id, $proyecto2->id]);

        $this->deleteJson("/api/materias/{$proyecto1->id}/cursada")->assertStatus(422);

        $this->assertDatabaseHas('materias_cursadas', [
            'user_id' => $this->user->id,
            'materia_id' => $proyecto1->id,
        ]);
    }

    public function test_una_cadena_de_prerrequisitos_se_valida_paso_a_paso(): void
    {
        $proyecto1 = $this->materia('LDSW105');
        $proyecto2 = $this->materia('LDSW206');
        $proyecto3 = $this->materia('LDSW306');

        $this->postJson("/api/materias/{$proyecto3->id}/cursada")->assertStatus(422);
        $this->postJson("/api/materias/{$proyecto1->id}/cursada")->assertOk();
        $this->postJson("/api/materias/{$proyecto3->id}/cursada")->assertStatus(422);
        $this->postJson("/api/materias/{$proyecto2->id}/cursada")->assertOk();
        $this->postJson("/api/materias/{$proyecto3->id}/cursada")->assertOk();
    }
}
