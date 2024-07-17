<?php

namespace Tests\Feature\Maker;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_maker_can_see_create_transaction_form(): void
    {
        $maker = User::factory()->maker()->create();
        $this->actingAs($maker);

        $response = $this->get('/dashboard');

        $response->assertSee(['Create Transaction', 'Type', 'Amount', 'Description']);
    }

    public function test_maker_without_wallet_can_not_create_transaction(): void
    {
        $maker = User::factory()->maker()->create();
        $this->actingAs($maker);

        $response = $this->post(route('transaction.create', [
            'user_id' => $maker->id,
            'type' => 'deposit',
            'amount' => 1000,
            'description' => 'Test transaction',
        ]));

        $this->assertDatabaseMissing('transactions', [
            'user_id' => $maker->id,
            'type' => 'deposit',
            'amount' => 1000,
            'description' => 'Test transaction',
            'status' => 'pending',
        ]);
    }

    public function test_maker_with_wallet_can_create_transaction_with_pending_status_and_see_it_in_transaction_history(): void
    {
        $maker = User::factory()->maker()->create();
        $this->actingAs($maker);

        $wallet = $this->post(route('wallet.create'), ['id' => $maker->id]);
        $response = $this->post(route('transaction.create', [
            'user_id' => $maker->id,
            'type' => 'deposit',
            'amount' => 1000,
            'description' => 'Test transaction',
        ]));

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('transactions', [
            'user_id' => $maker->id,
            'type' => 'deposit',
            'amount' => 1000,
            'description' => 'Test transaction',
            'status' => 'pending',
        ]);

        $history = $this->get('/dashboard');
        $history->assertSee(['Transaction History', 'Deposit', 'test transaction', '1,000.00', 'Pending']);
    }

    public function test_only_maker_can_create_transaction(): void
    {
        $checker = User::factory()->checker()->create();
        $this->actingAs($checker);

        $wallet = $this->post(route('wallet.create'), ['id' => $checker->id]);
        $response = $this->post(route('transaction.create', [
            'user_id' => $checker->id,
            'type' => 'deposit',
            'amount' => 1000,
            'description' => 'Test transaction',
        ]));

        $this->assertDatabaseMissing('transactions', [
            'user_id' => $checker->id,
            'type' => 'deposit',
            'amount' => 1000,
            'description' => 'Test transaction',
            'status' => 'pending',
        ]);
    }
}
