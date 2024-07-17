<?php

namespace Tests\Feature\Admin;

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

    public function test_admin_can_see_approve_and_reject_button_on_transaction_history_table(): void
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

        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);

        $response = $this->get(route('admin.dashboard'));

        $response->assertSee(['Transaction History', 'approve', 'reject', 'Deposit', 'test transaction', '1,000.00']);
    }
}
