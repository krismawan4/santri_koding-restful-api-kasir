<?php

use App\Models\User;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    /** @test */
    public function view_json_dashboard()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->json('GET', '/api/dashboard')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'best_selling' => [],
                    'low_quantity' => [],
                ],
                'error',
            ]);
    }
}
