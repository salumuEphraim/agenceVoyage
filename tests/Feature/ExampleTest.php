<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_home_alias_redirects_to_dashboard(): void
    {
        $response = $this->get('/home');

        $response->assertRedirect('/dashboard');
    }
}
