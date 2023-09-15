<?php

use App\Models\User;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    /** @test */
    public function view_json_dashboard()
    {
        $user = User::find(1);
        if (empty($user)) {
            $user = User::factory()->create();
        }

        $response = $this->actingAs($user)->json('GET', '/api/v1/dashboard');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'best_selling' => [],
                'low_quantity' => [],
            ],
            'error',
        ]);
    }
}
