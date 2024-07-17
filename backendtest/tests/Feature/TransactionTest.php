<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Transaction;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\DefaultUserSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $maker;
    protected $checker;

    public function setUp():void
    {
        parent::setUp();


        $this->seed(DatabaseSeeder::class);
        $this->admin = User::where('email', 'admin@gmail.com')->firstOrFail();
        $this->maker = User::where('email', 'maker@gmail.com')->firstOrFail();
        $this->checker = User::where('email', 'checker@gmail.com')->firstOrFail();

        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

    }

    public function test_user_can_view_transaction_page()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
    }

    public function test_maker_can_create_transaction()
    {
        $this->actingAs($this->maker);

        $response = $this->post(route('transaction.store'), [
            'type' => 'credit',
            'description' => 'Test Transaction',
            'amount' => 100,
        ]);
        $response->assertRedirect();
        $response->assertSessionHas('successMessage');

        $this->assertDatabaseHas('transactions', [
            'user_id' => $this->maker->id,
            'type' => 'credit',
            'description' => 'Test Transaction',
            'amount' => 100,
            'status' => 'pending',
        ]);
    }

    public function test_checker_cannot_create_transaction()
    {
        $this->actingAs($this->checker);

        $response = $this->post(route('transaction.store'), [
            'type' => 'credit',
            'description' => 'Test Transaction',
            'amount' => 100,
        ]);
        $response->assertStatus(403);
    }

    public function test_checker_can_approve_transaction()
    {

        $transaction = Transaction::create([
            'user_id' => $this->maker->id,
            'type' => 'credit',
            'amount' => 100,
            'status' => 'pending',
        ]);

        $this->actingAs($this->checker);
        $response = $this->post(route('transaction.approve',$transaction->id));

        $response->assertRedirect();
        $response->assertSessionHas('successMessage');
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'approved',
        ]);
        $this->assertDatabaseHas('wallets', [
            'user_id' => $this->maker->id,
            'balance' => 100,
        ]);
    }
    public function test_marker_cannot_approve_transaction()
    {

        $transaction = Transaction::create([
            'user_id' => $this->maker->id,
            'type' => 'credit',
            'amount' => 100,
            'status' => 'pending',
        ]);

        $this->actingAs($this->maker);
        $response = $this->post(route('transaction.approve',$transaction->id));

        $response->assertStatus(403);
    }



}
