<?php

namespace Tests\Feature;

use App\Models\Materia;
use App\Models\User;
use Database\Seeders\MateriaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * HU-15: reporte de propuesta de inscripción.
 */
class PropuestaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(MateriaSeeder::class);
    }

    public function test_la_propuesta_requiere_sesion(): void
    {
        $this->getJson('/api/propuesta')->assertUnauthorized();
    }

    public function test_un_alumno_nuevo_recibe_primero_las_materias_de_primer_semestre(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $respuesta = $this->getJson('/api/propuesta?max_materias=5')->assertOk();

        $materias = collect($respuesta->json('materias'));
        $this->assertCount(5, $materias);
        $this->assertSame([1], $materias->pluck('semestre')->unique()->values()->all());
        $this->assertSame($materias->sum('creditos'), $respuesta->json('total_creditos'));
    }

    public function test_la_propuesta_no_incluye_materias_cursadas_ni_bloqueadas(): void
    {
        $user = User::factory()->create();
        $proyecto1 = Materia::where('clave', 'LDSW105')->firstOrFail();
        $proyecto3 = Materia::where('clave', 'LDSW306')->firstOrFail();
        $user->materiasCursadas()->attach($proyecto1->id);
        Sanctum::actingAs($user);

        $respuesta = $this->getJson('/api/propuesta?max_materias=8')->assertOk();

        $ids = collect($respuesta->json('materias'))->pluck('id');
        $this->assertNotContains($proyecto1->id, $ids);
        $this->assertNotContains($proyecto3->id, $ids);
    }

    public function test_el_numero_de_materias_debe_estar_entre_1_y_8(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->getJson('/api/propuesta?max_materias=20')
            ->assertStatus(422)
            ->assertJsonValidationErrors('max_materias');
    }
}
