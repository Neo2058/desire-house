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
    public function test_the_home_page_is_not_found_until_it_is_created(): void
    {
        config(['setup.enabled' => false]);

        $response = $this->get('/');

        $response->assertNotFound();
    }
}
