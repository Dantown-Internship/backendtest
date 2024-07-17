<?php

namespace Tests\Feature\Checker;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_checker_get_to_the_right_dashboard(): void
    {
        $user = User::factory()->checker()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        // $response->assertRedirect(route('admin.dashboard'));
        $response->assertStatus(302);
    }

    public function test_checker_can_see_dashboard_info__transaction_history_and_transaction_activity(): void
    {
        $checker = User::factory()->checker()->create();
        $this->actingAs($checker);
        $response = $this->get(route('admin.dashboard'));

        $response->assertSee(['Total Transactions', 'Balance', 'Transaction Activity', 'Transaction History']);
    }

    public function test_checker_can_not_see_user_list_and_create_user(): void
    {
        $checker = User::factory()->checker()->create();
        $this->actingAs($checker);
        $response = $this->get('/dashboard');

        $response->assertDontSee(['Create User', 'User List']);
    }
}
