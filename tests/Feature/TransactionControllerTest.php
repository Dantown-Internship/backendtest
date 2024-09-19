<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\Pool;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\WithFaker;

class TransactionControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'maker']);
        Role::create(['name' => 'checker']);
    }

    /** @test */
    public function authenticated_user_can_create_a_transaction()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);
        $user->assignRole('maker');

        $this->actingAs($user);

        $response = $this->post('/transactions', [
            'type' => 'credit',
            'amount' => 100.00,
            'description' => 'Test transaction',
        ]);

        $response->assertRedirect('/transactions');
        $response->assertSessionHas('success', 'Transaction created successfully.');

        $transaction = Transaction::where('user_id', $user->id)->first();
        $this->assertNotNull($transaction);
        $this->assertEquals('credit', $transaction->type);
        $this->assertEquals(100.00, $transaction->amount);
    }

    /** @test */
    public function user_can_view_their_transactions()
    {
        $user = User::factory()->create();
        $user->assignRole('maker');

        $transaction = Transaction::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $response = $this->get('/transactions');

        $response->assertStatus(200);
        $response->assertSee($transaction->description);
    }

    /** @test */
    public function user_can_approve_a_transaction()
    {
        $user = User::factory()->create();
        $user->assignRole('checker');

        $pool = Pool::factory()->create(['balance' => 1000.00]);
        $wallet = Wallet::factory()->create(['user_id' => $user->id, 'balance' => 100.00]);
        $transaction = Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'credit',
            'amount' => 50.00,
            'status' => 'pending',
        ]);

        $this->actingAs($user);

        $response = $this->post('/transactions/' . $transaction->id . '/approve');

        $response->assertRedirect('/transactions/pending');
        $response->assertSessionHas('success', 'Transaction approved.');

        $transaction->refresh();
        $this->assertEquals('approved', $transaction->status);

        $wallet->refresh();
        $this->assertEquals(150.00, $wallet->balance);

        $pool->refresh();
        $this->assertEquals(950.00, $pool->balance);
    }

    /** @test */
    public function user_can_reject_a_transaction()
    {
        $user = User::factory()->create();
        $user->assignRole('checker');

        $transaction = Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'debit',
            'amount' => 50.00,
            'status' => 'pending',
        ]);

        $this->actingAs($user);

        $response = $this->post('/transactions/' . $transaction->id . '/reject');

        $response->assertRedirect('/transactions/pending');
        $response->assertSessionHas('success', 'Transaction rejected.');

        $transaction->refresh();
        $this->assertEquals('rejected', $transaction->status);
    }
}
