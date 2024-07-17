<?php

namespace Tests\Feature\Checker;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalletTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\AdminSeeder::class);
    }

    public function test_checker_can_see_system_wallet_balance(): void
    {
        $wallet = Wallet::find(1);

        $checker = User::factory()->checker()->create();
        $this->actingAs($checker);

        $response = $this->get(route('admin.dashboard'));

        $response->assertSee(['NGN', number_format($wallet->balance, 2)]);
    }

    public function test_checker_can_not_create_wallet(): void
    {
        $checker = User::factory()->checker()->create();
        $this->actingAs($checker);

        $response = $this->post(route('wallet.create'), ['id' => $checker->id]);

        $response->assertStatus(302);
        $this->assertDatabaseMissing('wallets', [
            'user_id' => $checker->id,
            'balance' => 0.00,
        ]);
    }
}
