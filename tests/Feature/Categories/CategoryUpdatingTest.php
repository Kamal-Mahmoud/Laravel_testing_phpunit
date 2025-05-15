<?php

namespace Tests\Feature\Categories;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryUpdatingTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }
    public function test_chack_if_categories_edit_page_contains_the_records(): void
    {
        $category = Category::factory()->create();
        $response = $this->get(route("categories.edit", $category));
        $response->assertStatus(200)
            ->assertViewIs('categories.edit')
            ->assertViewHas('category', $category)
            ->assertSee($category->name)
            ->assertSee($category->description);
    }


    public function test_chack_if_categories_update_page_contains_the_records(): void
    {
        $category = Category::factory()->create();
        $updatedCategory = [
            'name' => 'new name',
            'description' => 'new description'
        ];
        $response = $this->patch(route("categories.update", $category), $updatedCategory);
        $response->assertStatus(302)
            ->assertRedirect(route("categories.index"))
            ->assertSessionHas('success', 'Category updated successfully');
        $this->assertDatabaseHas('categories', $updatedCategory);
        $this->assertDatabaseMissing('categories', $category->toArray());
    }

    public function test_chack_category_name_is_required(): void
    {
        $category = Category::factory()->create();
        $updatedCategory = [

            'description' => 'new description'
        ];
        $response = $this->patch(route("categories.update", $category), $updatedCategory);
        $response->assertStatus(302)
            ->assertSessionHasErrors('name');
        $this->assertDatabaseMissing('categories', $updatedCategory);
        $this->assertDatabaseHas('categories', $category->toArray());
    }
}
