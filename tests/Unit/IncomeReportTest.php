<?php

use App\Models\User;
use Tests\TestCase;

class IncomeReportTest extends TestCase
{
    public function test_can_view_json_income_report()
    {
        $user = User::find(1);
        if (empty($user)) {
            $user = User::factory()->create();
        }

        $response = $this->actingAs($user)->json('GET', '/api/v1/reports/income-report?start_date=2021-01-01&end_date=' . date('Y-m-d'));

        $response->assertStatus(200);
    }
}
