<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryAuthorizationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_guest_cannot_access_categories_page(): void
    {
        $response = $this->get(route('categories.index'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_categories_create_page(): void
    {
        $response = $this->get(route('categories.create'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
    }
    public function test_guest_cannot_store_categories(): void
    {
        $category = Category::factory()->create();
        $response = $this->post(route('categories.store', $category));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
    }
    public function test_guest_cannot_update_categories(): void
    {
        $category = Category::factory()->create();
        $updatedCategory = Category::factory()->make();
        $response = $this->patch(route('categories.update', $category), $updatedCategory->toArray());

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    public function test_guest_cannot_delete_categories(): void
    {
        $category = Category::factory()->create();
        $response = $this->delete(route('categories.destroy', $category));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
    }
}
