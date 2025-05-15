<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;
    protected User $user;
    // protected function setUp(): void
    // {
    //     parent::setUp();
    //     $this->user = User::factory()->create();
    //     if ($this->name() !== 'test_prevent_unauthenticated_users') {
    //         Sanctum::actingAs($this->user);
    //     }
    // }

    protected function authenticatedUser(): void
    {
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }
    public function test_prevent_unauthenticated_users(): void
    {
        $response = $this->getJson('api/categories');

        $response->assertStatus(401);
    }
    public function test_list_all_categories(): void
    {
        $category = Category::factory()->count(5)->create();
        $this->authenticatedUser();
        $response = $this->getJson('api/categories');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
        ;
    }
    public function test_create_category(): void
    {
        $category = Category::factory()->make(); // make : data not saved into DB 
        $this->authenticatedUser();
        $response = $this->postJson("api/categories", $category->toArray());

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => $category->name]);
    }
    public function test_show_category(): void
    {
        $category = Category::factory()->create();
        $this->authenticatedUser();
        $response = $this->getJson("api/categories/$category->id");

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => $category->name]);
    }
    public function test_update_category(): void
    {
        $category = Category::factory()->create();
        $updatedCategory = [
            'name' => 'Updated Category',
            'description' => 'Updated Description'
        ];
        $this->authenticatedUser();
        $response = $this->patchJson("api/categories/$category->id", $updatedCategory);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => $updatedCategory['name']]);
    }
    public function test_delete_category(): void
    {
        $category = Category::factory()->create();
        $this->authenticatedUser();
        $response = $this->deleteJson("api/categories/$category->id");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('categories', $category->toArray());
    }
}
