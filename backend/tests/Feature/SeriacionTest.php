<?php

namespace Tests\Feature;

use App\Models\Materia;
use App\Models\User;
use Database\Seeders\MateriaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * HU-14: requisitos para cursar una materia y materias seriadas.
 */
class SeriacionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(MateriaSeeder::class);
    }

    public function test_la_seriacion_requiere_sesion(): void
    {
        $proyecto2 = Materia::where('clave', 'LDSW206')->firstOrFail();

        $this->getJson("/api/materias/{$proyecto2->id}/seriacion")->assertUnauthorized();
    }

    public function test_muestra_los_requisitos_y_si_ya_se_cumplieron(): void
    {
        $user = User::factory()->create();
        $proyecto1 = Materia::where('clave', 'LDSW105')->firstOrFail();
        $proyecto2 = Materia::where('clave', 'LDSW206')->firstOrFail();
        Sanctum::actingAs($user);

        $antes = $this->getJson("/api/materias/{$proyecto2->id}/seriacion")->assertOk();
        $antes->assertJson([
            'estado' => 'bloqueada',
            'requisitos' => [['clave' => 'LDSW105', 'cumplido' => false]],
        ]);

        $user->materiasCursadas()->attach($proyecto1->id);

        $despues = $this->getJson("/api/materias/{$proyecto2->id}/seriacion")->assertOk();
        $despues->assertJson([
            'estado' => 'disponible',
            'requisitos' => [['clave' => 'LDSW105', 'cumplido' => true]],
        ]);
    }

    public function test_muestra_las_materias_que_se_desbloquean(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $proyecto1 = Materia::where('clave', 'LDSW105')->firstOrFail();

        $respuesta = $this->getJson("/api/materias/{$proyecto1->id}/seriacion")->assertOk();

        $respuesta->assertJson(['requisitos' => []]);
        $this->assertContains('LDSW206', collect($respuesta->json('desbloquea'))->pluck('clave'));
    }
}
