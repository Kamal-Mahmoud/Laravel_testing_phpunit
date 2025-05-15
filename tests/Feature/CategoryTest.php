<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    public function test_example2(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    public function test_check_if_home_page_works_well(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
