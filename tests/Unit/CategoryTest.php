<?php

use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    /**
     * @test
     */
    public function can_return_a_collection_of_paginated_categories(): void
    {
        $category_count = Category::count();
        if ($category_count == 0) {
            Category::factory()->count(5)->create();
        }
        $user = User::find(1);
        if (empty($user)) {
            $user = User::factory()->create();
        }
        $response = $this->actingAs($user)->json('GET', '/api/v1/categories');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'current_page',
                'data' => [],
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'links' => [],
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total',
            ],
            'error',
        ]);
    }

    /**
     * @test
     */
    public function can_create_a_category(): void
    {
        $category = Category::factory()->make();
        $user = User::find(1);
        if (empty($user)) {
            $user = User::factory()->create();
        }
        $response = $this->actingAs($user)->json('POST', '/api/v1/categories', [
            'name' => $category->name . Str::random(5),
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            "success",
            "message",
            "data" => [
                "name",
                "slug",
                "updated_at",
                "created_at",
                "id",
                "image_url",
            ],
            "error",
        ]);
    }

    /**
     * @test
     */
    public function will_fail_with_a_404_if_category_is_not_found(): void
    {

        $user = User::find(1);
        if (empty($user)) {
            $user = User::factory()->create();
        }
        // Given
        // Table 999 does not exist.
        // When
        $response = $this->actingAs($user)->json('GET', '/api/v1/categories/999');
        // Then
        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function can_return_a_category(): void
    {
        $category = Category::find(1);
        if ($category == null) {
            $category = Category::factory()->create();
        }
        $user = User::find(1);
        if (empty($user)) {
            $user = User::factory()->create();
        }
        $response = $this->actingAs($user)->json('GET', '/api/v1/categories/' . $category->id);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "success",
            "message",
            "data" => [
                "id",
                "name",
                "slug",
                "created_at",
                "updated_at",
                "image_url",
            ],
            "error"
        ]);
    }

    /**
     * @test
     */
    public function can_update_a_category()
    {
        $category = Category::orderBy('id', 'desc')->first();
        if ($category == null) {
            $category = Category::factory()->create();
        }
        $user = User::find(1);
        if (empty($user)) {
            $user = User::factory()->create();
        }

        $newCategory = [
            'name' => $category->name . '_updated',
            'slug' => Str::slug($category->name . '-updated'),
        ];

        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($user)->json('PUT', '/api/v1/categories/' . $category->id, $newCategory);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "success",
            "message",
            "data" => [
                "id",
                "name",
                "slug",
                "created_at",
                "updated_at",
                "image_url",
            ],
            "error"
        ]);
    }

    /**
     * @test
     */
    public function will_fail_with_a_404_if_category_we_want_to_update_is_not_found(): void
    {
        // Given no category
        // When
        $user = User::find(1);
        if (empty($user)) {
            $user = User::factory()->create();
        }

        $response = $this->actingAs($user)->json('PUT', '/api/v1/categories/999', [
            'name' => 'OK updated',
        ]);
        $response->assertStatus(500);
        $response->assertJsonStructure([
            "success",
            "message",
            "data" => [
                "data",
            ],
            "error"
        ]);
    }

    /**
     * @test
     */
    public function will_fail_with_a_404_if_category_we_want_to_delete_is_not_found(): void
    {
        // Given
        // When
        $user = User::find(1);
        if (empty($user)) {
            $user = User::factory()->create();
        }

        $response = $this->actingAs($user)->json('DELETE', '/api/v1/categories/999');
        // Then
        $response->assertStatus(404);
        $response->assertJsonStructure([
            "success",
            "message",
            "data",
            "error"
        ]);
    }

    /**
     * @test
     */
    public function can_delete_a_category(): void
    {
        // Given
        $category = Category::orderBy('id', 'desc')->first();
        if ($category == null) {
            $category = Category::factory()->create();
        }
        // When
        $user = User::find(1);
        if (empty($user)) {
            $user = User::factory()->create();
        }

        $response = $this->actingAs($user)->json('DELETE', '/api/v1/categories/' . $category->id);
        // Then
        $response->assertStatus(200);
    }
}
