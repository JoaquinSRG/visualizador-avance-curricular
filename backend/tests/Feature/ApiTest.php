<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * La dirección base de la API responde, para validar que está en línea.
 */
class ApiTest extends TestCase
{
    public function test_la_direccion_base_de_la_api_responde(): void
    {
        $this->getJson('/api')
            ->assertOk()
            ->assertJson(['estado' => 'en línea']);
    }

    public function test_la_raiz_del_servidor_redirige_al_sitio(): void
    {
        $this->get('/')
            ->assertStatus(302)
                    ->assertHeader('Location', 'https://joaquinsrg.github.io/visualizador-avance-curricular');
    }
}
