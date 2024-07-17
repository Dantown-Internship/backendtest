<?php

namespace Tests\Feature\Checker;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\AdminSeeder::class);
    }

    public function test_checker_can_not_see_create_user_form(): void
    {
        $checker = User::factory()->checker()->create();
        $this->actingAs($checker);

        $response = $this->get(route('admin.dashboard'));

        $response->assertDontSee(['Create Checker', 'Email']);
    }

    public function test_checker_can_see_approve_and_reject_button_on_transaction_history_table(): void
    {
        $maker = User::factory()->maker()->create();
        $this->actingAs($maker);

        $wallet = $this->post(route('wallet.create'), ['id' => $maker->id]);
        $transaction = $this->post(route('transaction.create', [
            'user_id' => $maker->id,
            'type' => 'deposit',
            'amount' => 1000,
            'description' => 'Test transaction',
        ]));

        $checker = User::factory()->checker()->create();
        $this->actingAs($checker);

        $response = $this->get(route('admin.dashboard'));

        $response->assertSee(['Transaction History', 'approve', 'reject', 'Deposit', 'test transaction', '1,000.00']);
    }
}
