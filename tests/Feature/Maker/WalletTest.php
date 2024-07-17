<?php

namespace Tests\Feature\Maker;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalletTest extends TestCase
{
    use RefreshDatabase;

    public function test_maker_without_wallet_can_not_see_balance_but_create_wallet_button(): void
    {
        $maker = User::factory()->maker()->create();
        $this->actingAs($maker);

        $response = $this->get('/dashboard');

        $response->assertSee(['Balance', 'Create Wallet']);
        $response->assertDontSeeText('NGN');
    }

    public function test_maker_can_create_wallet_with_zero_balance(): void
    {
        $maker = User::factory()->maker()->create();
        $this->actingAs($maker);

        $response = $this->post(route('wallet.create'), ['id' => $maker->id]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('wallets', [
            'user_id' => $maker->id,
            'balance' => 0.00,
        ]);
    }

    public function test_maker_with_wallet_can_see_balance_and_not_create_wallet_button(): void
    {
        $maker = User::factory()->maker()->create();
        $this->actingAs($maker);

        $response = $this->post(route('wallet.create'), ['id' => $maker->id]);
        $response = $this->get('/dashboard');

        $response->assertDontSeeText('Create Wallet');
        $response->assertSee(['Balance', 'NGN']);
    }
}
