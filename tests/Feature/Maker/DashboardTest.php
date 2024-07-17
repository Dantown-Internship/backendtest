<?php

namespace Tests\Feature\Maker;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_maker_get_to_the_right_dashboard(): void
    {
        $user = User::factory()->maker()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('user.dashboard'));
    }

    public function test_maker_can_see_dashboard_info_transaction_form_and_transaction_history(): void
    {
        $maker = User::factory()->maker()->create();
        $this->actingAs($maker);
        $response = $this->get('/dashboard');

        $response->assertSee(['Total Transactions', 'Balance', 'Create Transaction', 'Transaction History']);
    }
}
