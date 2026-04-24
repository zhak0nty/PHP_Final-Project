<?php

namespace Tests\Feature;

use Tests\TestCase;

class AppStabilityTest extends TestCase
{
    public function test_not_found_page_renders_friendly_404(): void
    {
        $this->get('/this-route-should-not-exist-12345')
            ->assertNotFound()
            ->assertSee('Page not found', false);
    }

    public function test_unknown_api_routes_respond_with_json(): void
    {
        $response = $this->getJson('/api/this-endpoint-does-not-exist-999');

        $response->assertNotFound();
        $this->assertStringContainsString('json', (string) $response->headers->get('Content-Type'));
    }
}
