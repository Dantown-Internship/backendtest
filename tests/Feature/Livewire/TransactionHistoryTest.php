<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Admin\TransactionHistory;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TransactionHistoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function renders_successfully()
    {
        Livewire::test(TransactionHistory::class)
            ->assertStatus(200);
    }

    public function test_maker_not_authurize_to__approve_or_reject_a_transaction()
    {
        $maker = User::factory()->maker()->create();
        $checker = User::factory()->checker()->create();
        $admin = User::factory()->admin()->create();

        $wallet = Wallet::create(['user_id' => $maker->id]);
        $transaction = Transaction::create([
            'user_id' => $maker->id,
            'type' => 'deposit',
            'amount' => 2000,
            'description' => 'Test transaction',
        ]);

        Livewire::actingAs($maker)
            ->test(TransactionHistory::class)
            ->set('id', $transaction->id)
            ->set('action', 'approved')
            ->call('confirmUserAction')
            ->assertHasNoErrors()
            ->assertForbidden();
    }

    public function test_checker_and_admin_can__approve_or_reject_a_transaction()
    {
        $maker = User::factory()->maker()->create();
        $checker = User::factory()->checker()->create();
        $admin = User::factory()->admin()->create();

        $wallet = Wallet::create(['user_id' => $maker->id]);
        $transaction = Transaction::create([
            'user_id' => $maker->id,
            'type' => 'deposit',
            'amount' => 2000,
            'description' => 'Test transaction',
        ]);

        Livewire::actingAs($checker)
            ->test(TransactionHistory::class)
            ->set('id', $transaction->id)
            ->set('action', 'approve')
            ->call('confirmUserAction')
            ->assertHasNoErrors()
            ->assertSee(ucfirst($transaction->type))
            ->assertSee(strtolower($transaction->description))
            ->assertSee(number_format($transaction->amount))
            ->assertSee(ucfirst('approved'));

        Livewire::actingAs($admin)
            ->test(TransactionHistory::class)
            ->set('id', $transaction->id)
            ->set('action', 'rejecte')
            ->call('confirmUserAction')
            ->assertHasNoErrors()
            ->assertSee(ucfirst($transaction->type))
            ->assertSee(strtolower($transaction->description))
            ->assertSee(number_format($transaction->amount))
            ->assertSee(ucfirst('rejected'));
    }
}
