<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_estudiante_puede_registrarse(): void
    {
        $respuesta = $this->postJson('/api/register', [
            'name' => 'Ana',
            'email' => 'ana@example.com',
            'password' => 'secreto123',
            'password_confirmation' => 'secreto123',
        ]);

        $respuesta->assertStatus(201)->assertJsonStructure(['user', 'token']);
        $this->assertDatabaseHas('users', ['email' => 'ana@example.com']);
    }

    public function test_el_registro_rechaza_un_correo_repetido_con_mensaje_en_espanol(): void
    {
        User::factory()->create(['email' => 'ana@example.com']);

        $respuesta = $this->postJson('/api/register', [
            'name' => 'Ana',
            'email' => 'ana@example.com',
            'password' => 'secreto123',
            'password_confirmation' => 'secreto123',
        ]);

        $respuesta->assertStatus(422)->assertJsonValidationErrors([
            'email' => 'Ya existe una cuenta con ese correo.',
        ]);
    }

    public function test_un_estudiante_puede_iniciar_sesion(): void
    {
        User::factory()->create([
            'email' => 'ana@example.com',
            'password' => 'secreto123',
        ]);

        $respuesta = $this->postJson('/api/login', [
            'email' => 'ana@example.com',
            'password' => 'secreto123',
        ]);

        $respuesta->assertOk()->assertJsonStructure(['user', 'token']);
    }

    public function test_el_login_rechaza_una_contrasena_incorrecta(): void
    {
        User::factory()->create([
            'email' => 'ana@example.com',
            'password' => 'secreto123',
        ]);

        $respuesta = $this->postJson('/api/login', [
            'email' => 'ana@example.com',
            'password' => 'otra-clave',
        ]);

        $respuesta->assertStatus(422)->assertJsonValidationErrors(['email']);
    }

    public function test_las_rutas_protegidas_rechazan_peticiones_sin_token(): void
    {
        $this->getJson('/api/me')->assertUnauthorized();
    }
}
