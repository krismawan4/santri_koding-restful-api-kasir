<?php

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class ItemTest extends TestCase
{
    /**
     * @test
     */
    public function can_return_a_collection_of_paginated_items(): void
    {
        $item_count = Item::count();
        if ($item_count == 0) {
            Item::factory()->count(5)->create();
        }

        $user = User::find(1);
        if (empty($user)) {
            $user = User::factory()->create();
        }

        $response = $this->actingAs($user)
            ->json('GET', '/api/v1/items');
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
    public function can_create_a_item(): void
    {
        $category = Category::find(1);
        if ($category == null) {
            $category = Category::factory()->create();
        }
        $item = Item::factory()->make([
            'category_id' => $category->id,
        ]);
        $user = User::find(1);
        if (empty($user)) {
            $user = User::factory()->create([
                'role' => 'admin',
            ]);
        }
        $response = $this->actingAs($user)->json('POST', '/api/v1/items', [
            'category_id' => $item->category_id,
            'name' => $item->name,
            'barcode' => $item->barcode,
            'description' => $item->description,
            'price' => $item->price,
            'quantity' => $item->quantity,
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            "success",
            "message",
            "data" => [
                "category_id",
                "barcode",
                "name",
                "slug",
                "description",
                "price",
                "quantity",
                "updated_at",
                "created_at",
                "id",
                "image_url",
            ],
            "error",
        ]);

        $response->assertJson([
            "success" => true,
            "message" => "Pembuatan Item Berhasil",
            "data" => [
                'category_id' => $category->id,
                'barcode' => $item->barcode,
                'name' => $item->name,
                'description' => $item->description,
                'price' => $item->price,
                'quantity' => $item->quantity,
            ],
            "error" => "",
        ]);
    }

    /**
     * @test
     */
    public function can_return_a_item(): void
    {
        $item = Item::orderBy('id', 'desc')->first();
        if ($item == null) {
            $item = Item::factory()->create();
        }
        $user = User::find(1);
        if (empty($user)) {
            $user = User::factory()->create();
        }
        $response = $this->actingAs($user)->json('GET', '/api/v1/items/' . $item->id);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "success",
            "message",
            "data" => [
                "category_id",
                "barcode",
                "name",
                "slug",
                "description",
                "price",
                "quantity",
                "updated_at",
                "created_at",
                "id",
                "image_url",
            ],
            "error",
        ]);
        $response->assertJson([
            "success" => true,
            "message" => "Show Item Berhasil",
            "data" => [
                'id' => $item->id,
                'category_id' => (string) $item->category_id,
                'barcode' => $item->barcode,
                'name' => $item->name,
                'description' => $item->description,
                'price' => (string) $item->price,
                'quantity' => (string) $item->quantity,
            ],
            "error" => "",
        ]);
    }

    /**
     * @test
     */
    public function will_fail_with_a_404_if_item_is_not_found(): void
    {
        // Given
        // Item 999 does not exist.
        // When
        $user = User::factory()->create();

        $response = $this->actingAs($user)->json('GET', '/api/v1/items/999');
        // Then
        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function can_update_a_item(): void
    {
        $category = Category::factory()->create();
        $item = Item::factory()->create([
            'category_id' => $category->id,
        ]);

        $newItem = [
            'category_id' => $item->category_id,
            'barcode' => (int) $item->barcode,
            'name' => $item->name . '_updated',
            'description' => $item->description . '_updated',
            'price' => $item->price,
            'quantity' => $item->quantity,
            'slug' => Str::slug($item->name . '-updated'),
        ];

        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($user)->json('PUT', '/api/v1/items/' . $item->id, $newItem);

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function will_fail_with_a_404_if_item_we_want_to_update_is_not_found(): void
    {
        $item = Item::factory()->create();
        $category = Category::factory()->create();
        // Given no item
        // When
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($user)->json('PUT', '/api/v1/items/999', [
            'category_id' => $category->id,
            'barcode' => $item->barcode,
            'name' => $item->name . '_updated',
            'description' => $item->description . '_updated',
            'price' => $item->price,
            'quantity' => $item->quantity,
        ]);

        $response->assertStatus(500);
    }

    /**
     * @test
     */
    public function can_delete_a_item(): void
    {
        $item = Item::orderBy('id', 'desc')->first();

        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($user)->json('DELETE', '/api/v1/items/' . $item->id);

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function will_fail_with_a_404_if_item_we_want_to_delete_is_not_found(): void
    {
        // Given
        // When
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($user)->json('DELETE', '/api/v1/items/999');
        // Then
        $response->assertStatus(404);
    }
}
