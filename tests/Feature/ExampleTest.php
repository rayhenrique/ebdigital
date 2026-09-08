<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function test_assetlinks_json_is_accessible_and_valid(): void
    {
        $response = $this->get('/.well-known/assetlinks.json');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
        $data = $response->json();
        $this->assertIsArray($data);
        $this->assertEquals('br.com.adteotoniovilela.cadernetaebd', $data[0]['target']['package_name']);
    }

    public function test_privacy_policy_page_is_publicly_accessible_and_contains_google_play_identifiers(): void
    {
        $response = $this->get('/politica-de-privacidade');

        $response->assertStatus(200);
        $response->assertSee('Caderneta EBD Online');
        $response->assertSee('br.com.adteotoniovilela.cadernetaebd');
        $response->assertSee('Ray Henrique');
        $response->assertSee('rayhenrique@gmail.com');
        $response->assertSee('Assembleia de Deus');
    }

    public function test_privacy_policy_alias_routes_work(): void
    {
        $this->get('/privacidade')->assertStatus(200);
        $this->get('/privacy-policy')->assertStatus(200);
    }

    public function test_login_screen_contains_privacy_policy_and_developer_link(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Política de Privacidade');
        $response->assertSee('KL Tecnologia');
        $response->assertSee('https://kltecnologia.com');
    }
}
