<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExploreDirectoriesTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_explore_creators_page_renders_successfully(): void
    {
        $response = $this->get(route('explore.creators'));

        $response->assertStatus(200);
        $response->assertSee('CREATOR BANK');
        $response->assertSee('Temukan Creator');
    }

    public function test_explore_brands_page_renders_successfully(): void
    {
        $response = $this->get(route('explore.brands'));

        $response->assertStatus(200);
        $response->assertSee('BRAND BANK');
        $response->assertSee('Temukan Brand');
    }
}
