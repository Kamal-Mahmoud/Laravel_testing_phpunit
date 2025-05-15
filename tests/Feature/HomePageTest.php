<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_check_if_home_page_works_well(): void
    {
        $response = $this->get(route("index"));

        $response->assertStatus(200);
        $response->assertViewIs("welcome");
        $response->assertSeeText("Log in");
        $response->assertSeeText("Register");
    }
}
