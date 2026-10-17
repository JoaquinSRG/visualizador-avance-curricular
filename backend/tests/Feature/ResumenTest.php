<?php

namespace Tests\Feature;

use App\Models\Materia;
use App\Models\User;
use Database\Seeders\MateriaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * HU-10: resumen del avance del estudiante.
 */
class ResumenTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(MateriaSeeder::class);
    }

    public function test_el_resumen_requiere_sesion(): void
    {
        $this->getJson('/api/resumen')->assertUnauthorized();
    }

    public function test_el_resumen_de_un_estudiante_nuevo_empieza_en_cero(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $respuesta = $this->getJson('/api/resumen')->assertOk();

        $respuesta->assertJson([
            'total_materias' => 44,
            'cursadas' => 0,
            'porcentaje' => 0,
            'creditos_cursados' => 0,
        ]);
        $this->assertSame(44, $respuesta->json('disponibles') + $respuesta->json('bloqueadas'));
        $this->assertCount($respuesta->json('disponibles'), $respuesta->json('para_inscribir'));
    }

    public function test_el_resumen_cuenta_las_materias_y_creditos_cursados(): void
    {
        $user = User::factory()->create();
        $proyecto1 = Materia::where('clave', 'LDSW105')->firstOrFail();
        $proyecto2 = Materia::where('clave', 'LDSW206')->firstOrFail();
        $user->materiasCursadas()->attach($proyecto1->id);
        Sanctum::actingAs($user);

        $respuesta = $this->getJson('/api/resumen')->assertOk();

        $respuesta->assertJson([
            'cursadas' => 1,
            'porcentaje' => 2,
            'creditos_cursados' => $proyecto1->creditos,
        ]);

        // Proyecto II se desbloqueó y ya aparece en las que puede inscribir
        $this->assertContains($proyecto2->id, collect($respuesta->json('para_inscribir'))->pluck('id'));
    }
}
