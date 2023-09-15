<?php

use App\Models\Table;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class TableTest extends TestCase
{
    /**
     * @test
     */
    public function can_create_a_table(): void
    {
        $table = Table::factory()->make();
        $name = $table->name;

        $user = User::find(1);
        if (empty($user)) {
            $user = User::factory()->create([
                'role' => 'admin',
            ]);
        }

        $response = $this->actingAs($user)->json('POST', '/api/v1/tables', [
            'name' => $name . Str::random(10),
        ]);

        // Then
        // The return response code is 'Created' (201)
        $response->assertStatus(201);
    }

    /**
     * @test
     */
    public function can_return_a_table(): void
    {
        // Given
        $table = Table::latest()->first();

        // When
        $user = User::find(1);
        if (empty($user)) {
            $user = User::factory()->create();
        }

        $response = $this->actingAs($user)->json('GET', '/api/v1/tables/' . $table->id);

        // Then
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function will_fail_with_a_404_if_table_is_not_found(): void
    {
        // Given
        // Table 999 does not exist.
        // When
        $user = User::find(1);
        if (empty($user)) {
            $user = User::factory()->create();
        }

        $response = $this->actingAs($user)->json('GET', '/api/v1/tables/999');
        // Then
        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function will_fail_with_a_404_if_table_we_want_to_update_is_not_found(): void
    {
        // Given no table
        // When
        $user = User::find(1);
        if (empty($user)) {
            $user = User::factory()->create([
                'role' => 'admin',
            ]);
        }

        $response = $this->actingAs($user)->json('PUT', '/api/v1/tables/999', [
            'name' => 'OK updated',
        ]);

        // Then
        $response->assertStatus(500);
    }

    /**
     * @test
     */
    public function can_update_a_table(): void
    {
        // Given
        $table = Table::latest()->first();

        // When
        $newTable = [
            'name' => $table->name . '_updated' . Str::random(10),
        ];

        $user = User::find(1);
        if (empty($user)) {
            $user = User::factory()->create([
                'role' => 'admin',
            ]);
        }

        $response = $this->actingAs($user)->json('PUT', '/api/v1/tables/' . $table->id, $newTable);

        // Then
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function will_fail_with_a_404_if_table_we_want_to_delete_is_not_found(): void
    {
        // Given
        // When
        $user = User::find(1);
        if (empty($user)) {
            $user = User::factory()->create([
                'role' => 'admin',
            ]);
        }

        $response = $this->actingAs($user)->json('DELETE', '/api/v1/tables/999');
        // Then
        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function can_delete_a_table(): void
    {
        // Given
        $table = Table::latest()->first();
        // When
        $user = User::find(1);
        if (empty($user)) {
            $user = User::factory()->create([
                'role' => 'admin',
            ]);
        }

        $response = $this->actingAs($user)->json('DELETE', '/api/v1/tables/' . $table->id);
        // Then
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function can_return_a_collection_of_paginated_tables(): void
    {
        $count = Table::count();
        if ($count == 0) {
            Table::factory()->count(3)->create();
        }

        $user = User::find(1);
        if (empty($user)) {
            $user = User::factory()->create();
        }

        $response = $this->actingAs($user)->json('GET', '/api/v1/tables');

        $response->assertStatus(200);

        // Then
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
}
