<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use Spatie\Permission\Models\Role;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testAuthenticatedUserCanCreateATransaction(): void
    {
        $user = User::factory()->create();
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
    
}
