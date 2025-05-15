<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryCreationTest extends TestCase
{

    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }
    public function test_check_category_create_page_render_successfully(): void
    {
        $response = $this->actingAs($this->user)->get(route('categories.create'));

        $response->assertStatus(200)
            ->assertViewIs("categories.create")
            ->assertSeeText("Name")
            ->assertSeeText("Description");
    }
    public function test_create_category(): void
    {
        $categoty = Category::factory()->make();
        $response = $this->actingAs($this->user)->post(route('categories.store'), $categoty->toArray());

        $response->assertStatus(302)
            ->assertRedirect(route('categories.index'))
            ->assertSessionHas('success', 'Category created successfully')
        ;
        $this->assertDatabaseHas('categories', $categoty->toArray());  // take : 1- table name  , 2- data created
    }
    public function test_category_name_is_required(): void
    {
        $categoty = ['description' => 'description'];
        $response = $this->actingAs($this->user)->post(route('categories.store'), $categoty);

        $response->assertStatus(302)
            ->assertSessionHasErrors('name', 'The name field is required.')
        ;
        $this->assertDatabaseMissing('categories', $categoty);  // take : 1- table name  , 2- data created
    }
    public function test_category_name_must_atleast_3_characters(): void
    {
        $categoty = ['name' => 'dx', 'description' => 'description'];
        $response = $this->actingAs($this->user)->post(route('categories.store'), $categoty);

        $response->assertStatus(302)
            ->assertSessionHasErrors('name')
        ;
        $this->assertDatabaseMissing('categories', $categoty);  // take : 1- table name  , 2- data created
    }

    // public function test_check_category_show_page_contains_the_right_content(): void
    // {
    //     $categoty = Category::factory()->create();
    //     $response = $this->actingAs($this->user)->get(route('categories.create'));

    //     $response->assertStatus(200)
    //         ->assertViewIs("categories.create")
    //         ->assertSeeText("Name")
    //         ->assertSeeText("Description");
    // }
}
