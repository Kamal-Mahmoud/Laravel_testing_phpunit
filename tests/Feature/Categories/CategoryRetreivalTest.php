<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryRetreivalTest extends TestCase
{
    use RefreshDatabase; // arrange , act , assert 

    /**
     * A basic feature test example.
     */
    // protected User $user;
    protected function setUp(): void   // run before every function is used
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }
    public function test_chack_if_categories_page_open_correctly(): void
    {
        $response = $this->get('/categories');
        $response->assertStatus(200)
            ->assertViewIs('categories.index')
            ->assertSeeText('Categories');
    }
    public function test_check_if_category_page_contains_category(): void
    {

        Category::factory()->count(5)->create();

        $response = $this->get('/categories');
        $response->assertViewHas('categories', function ($categories) {
            return $categories->count() == 5;
        });
    }

    public function test_check_if_pagination_works(): void
    {

        Category::factory()->count(15)->create();

        $response = $this->get('/categories');
        $response->assertViewHas('categories', function ($categories) {
            return $categories->count() === 10;
        });

        $response = $this->get('/categories?page=2');
        $response->assertViewHas('categories', function ($categories) {
            return $categories->count() === 5;
        });
    }
    public function test_chack_if_categorie_show_page_contains_the_right_content(): void
    {
        $category = Category::factory()->create();
        $response = $this->get(route('categories.show', ['category' => $category]));
        $response->assertStatus(200)
            ->assertViewIs('categories.show')
            ->assertViewHas('category', $category)
            ->assertSeeText($category->name)
            ->assertSeeText($category->description)
            ;
    }
}
