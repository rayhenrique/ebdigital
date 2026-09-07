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
}
