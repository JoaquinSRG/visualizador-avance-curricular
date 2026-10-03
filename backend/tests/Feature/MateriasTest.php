<?php

namespace Tests\Feature;

use App\Models\Materia;
use App\Models\User;
use Database\Seeders\MateriaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class MateriasTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(MateriaSeeder::class);
    }

    public function test_la_lista_de_materias_requiere_sesion(): void
    {
        $this->getJson('/api/materias')->assertUnauthorized();
    }

    public function test_se_listan_las_44_materias_del_plan_de_estudios(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $respuesta = $this->getJson('/api/materias');

        $respuesta->assertOk()->assertJsonCount(44);
        $respuesta->assertJsonStructure([
            '*' => ['id', 'clave', 'nombre', 'semestre', 'creditos', 'cursada', 'prerrequisitos'],
        ]);
    }

    public function test_un_estudiante_puede_marcar_una_materia_como_cursada(): void
    {
        $user = User::factory()->create();
        $materia = Materia::first();
        Sanctum::actingAs($user);

        $this->postJson("/api/materias/{$materia->id}/cursada")
            ->assertOk()
            ->assertJson(['cursada' => true]);

        $this->assertDatabaseHas('materias_cursadas', [
            'user_id' => $user->id,
            'materia_id' => $materia->id,
        ]);

        $this->getJson('/api/materias')
            ->assertJsonFragment(['id' => $materia->id, 'cursada' => true]);
    }

    public function test_un_estudiante_puede_desmarcar_una_materia(): void
    {
        $user = User::factory()->create();
        $materia = Materia::first();
        $user->materiasCursadas()->attach($materia->id);
        Sanctum::actingAs($user);

        $this->deleteJson("/api/materias/{$materia->id}/cursada")
            ->assertOk()
            ->assertJson(['cursada' => false]);

        $this->assertDatabaseMissing('materias_cursadas', [
            'user_id' => $user->id,
            'materia_id' => $materia->id,
        ]);
    }

    public function test_el_avance_de_un_estudiante_no_afecta_a_otro(): void
    {
        $ana = User::factory()->create();
        $luis = User::factory()->create();
        $materia = Materia::first();
        $ana->materiasCursadas()->attach($materia->id);

        Sanctum::actingAs($luis);

        $this->getJson('/api/materias')
            ->assertJsonFragment(['id' => $materia->id, 'cursada' => false]);
    }
}
