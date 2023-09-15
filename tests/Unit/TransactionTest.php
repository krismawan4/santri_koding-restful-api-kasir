<?php

use App\Models\Item;
use App\Models\Table;
use App\Models\Transaction;
use App\Models\User;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    /**
     * @test
     */
    public function can_return_a_collection_of_paginated_transactions(): void
    {
        Transaction::factory()->count(1)->create();

        $user = User::find(1);
        if (empty($user)) {
            $user = User::factory()->create();
        }

        $response = $this->actingAs($user)->json('GET', '/api/v1/transactions');
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
    public function can_create_a_transaction(): void
    {
        $table = Table::factory()->create();
        $item = Item::factory()->create();

        $user = User::find(2);
        if (empty($user)) {
            $user = User::factory()->create([
                'role' => 'kasir',
            ]);
        }

        $response = $this->actingAs($user)->json('POST', '/api/v1/transactions', [
            'table_id' => $table->id,
            'total' => (50000 * 4),
            'pay' => 200000,
            'change' => 0,
            'status' => 'success',
            'item_id' => [
                0 => $item->id,
            ],
            'quantity' => [
                0 => 4,
            ],
            'price' => [
                0 => 50000,
            ],
        ]);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'data' => [
                'user_id',
                'table_id',
                'total',
                'pay',
                'change',
                'status',
                'invoice',
                'updated_at',
                'created_at',
                'id',
            ],
            'error',
        ]);
    }

    /**
     * @test
     */
    public function can_return_a_transaction(): void
    {
        $transaction = Transaction::factory()->create();

        $user = User::find(1);
        if (empty($user)) {
            $user = User::factory()->create();
        }

        $response = $this->actingAs($user)->json('GET', '/api/v1/transactions/' . $transaction->invoice);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'user_id',
                'table_id',
                'total',
                'pay',
                'change',
                'status',
                'invoice',
                'updated_at',
                'created_at',
                'id',
            ],
            'error',
        ]);
    }

    /**
     * @test
     */
    public function will_fail_with_a_404_if_transaction_is_not_found(): void
    {
        // Given
        // Table 999 does not exist.
        // When
        $user = User::find(1);
        if (empty($user)) {
            $user = User::factory()->create();
        }

        $response = $this->actingAs($user)->json('GET', '/api/v1/transaction/INV-999999');
        // Then
        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function can_update_a_transaction(): void
    {
        // Given
        $transaction = Transaction::factory(['status' => 'pending'])->create();
        $table = Table::factory()->create();
        $item = Item::factory()->create();
        // When
        $update = [
            'table_id' => $table->id,
            'total' => (50000 * 4),
            'pay' => 200000,
            'change' => 0,
            'status' => 'success',
            'item_id' => [
                0 => $item->id,
            ],
            'quantity' => [
                0 => 4,
            ],
            'price' => [
                0 => 50000,
            ],
        ];

        $user = User::find(2);
        if (empty($user)) {
            $user = User::factory()->create([
                'role' => 'kasir',
            ]);
        }

        $response = $this->actingAs($user)->json('PUT', '/api/v1/transactions/' . $transaction->invoice, $update);

        // Then
        $response->assertStatus(200);
    }
}
