<?php

namespace Tests\Feature;

use App\Models\Materia;
use App\Models\User;
use Database\Seeders\MateriaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * HU-13: kárdex del alumno con calificaciones y créditos.
 */
class KardexTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(MateriaSeeder::class);
    }

    public function test_el_kardex_requiere_sesion(): void
    {
        $this->getJson('/api/kardex')->assertUnauthorized();
    }

    public function test_el_kardex_de_un_alumno_nuevo_esta_vacio(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $respuesta = $this->getJson('/api/kardex')->assertOk();

        $respuesta->assertJson([
            'materias_cursadas' => 0,
            'creditos_cursados' => 0,
            'promedio_general' => null,
            'semestres' => [],
        ]);
        $this->assertSame(
            $respuesta->json('creditos_totales'),
            $respuesta->json('creditos_faltantes')
        );
    }

    public function test_el_kardex_muestra_calificaciones_creditos_y_promedio(): void
    {
        $user = User::factory()->create();
        $proyecto1 = Materia::where('clave', 'LDSW105')->firstOrFail();
        $programacion = Materia::where('clave', 'LDSW102')->firstOrFail();
        $user->materiasCursadas()->attach($proyecto1->id, ['calificacion' => 90]);
        $user->materiasCursadas()->attach($programacion->id, ['calificacion' => 85]);
        Sanctum::actingAs($user);

        $respuesta = $this->getJson('/api/kardex')->assertOk();

        $respuesta->assertJson([
            'materias_cursadas' => 2,
            'creditos_cursados' => $proyecto1->creditos + $programacion->creditos,
            'promedio_general' => 87.5,
        ]);
        $this->assertSame(1, $respuesta->json('semestres.0.semestre'));
        $this->assertCount(2, $respuesta->json('semestres.0.materias'));
    }

    public function test_se_puede_guardar_la_calificacion_de_una_materia_cursada(): void
    {
        $user = User::factory()->create();
        $proyecto1 = Materia::where('clave', 'LDSW105')->firstOrFail();
        $user->materiasCursadas()->attach($proyecto1->id);
        Sanctum::actingAs($user);

        $this->patchJson("/api/materias/{$proyecto1->id}/calificacion", ['calificacion' => 95])
            ->assertOk()
            ->assertJson(['calificacion' => 95]);

        $this->assertDatabaseHas('materias_cursadas', [
            'user_id' => $user->id,
            'materia_id' => $proyecto1->id,
            'calificacion' => 95,
        ]);
    }

    public function test_no_se_puede_calificar_una_materia_que_no_esta_cursada(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $proyecto1 = Materia::where('clave', 'LDSW105')->firstOrFail();

        $this->patchJson("/api/materias/{$proyecto1->id}/calificacion", ['calificacion' => 80])
            ->assertStatus(422);
    }

    public function test_la_calificacion_debe_estar_entre_0_y_100(): void
    {
        $user = User::factory()->create();
        $proyecto1 = Materia::where('clave', 'LDSW105')->firstOrFail();
        $user->materiasCursadas()->attach($proyecto1->id);
        Sanctum::actingAs($user);

        $this->patchJson("/api/materias/{$proyecto1->id}/calificacion", ['calificacion' => 120])
            ->assertStatus(422)
            ->assertJsonValidationErrors('calificacion');
    }
}
