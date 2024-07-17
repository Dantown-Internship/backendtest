<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\SystemPoolBalance;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\DefaultUserSeeder;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\Transactions\TransactionController;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $maker;
    protected $checker;
    protected $controller;

    public function setUp():void
    {
        parent::setUp();
      //  $this->artisan('db:seed', ['--class' => DatabaseSeeder::class]);
        $this->seed(DatabaseSeeder::class);
        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        $this->admin = User::where('email', 'admin@gmail.com')->firstOrFail();
        $this->maker = User::where('email', 'maker@gmail.com')->firstOrFail();
        $this->checker = User::where('email', 'checker@gmail.com')->firstOrFail();

        $this->controller = new TransactionController();
    }

    public function test_store_transaction_method()
    {
        $request = Request::create(route('transaction.store'), 'POST', [
            'type' => 'credit',
            'description' => 'Test Transaction',
            'amount' => 100,
        ]);

        $this->actingAs($this->maker);

        // Start and set the session
        Session::start();
        $session = app('session.store');
        $request->setLaravelSession($session);

        $response = $this->controller->store($request);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertTrue(Session::has('successMessage'));

        $this->assertDatabaseHas('transactions', [
            'user_id' => $this->maker->id,
            'type' => 'credit',
            'amount' => 100,
            'status' => 'pending',
        ]);
    }

    public function test_approve_transaction_method()
    {
        $transaction = Transaction::create([
            'user_id' => $this->maker->id,
            'type' => 'credit',
            'amount' => 100,
            'status' => 'pending',
        ]);

        $systemPoolBalance = SystemPoolBalance::first();

        $this->actingAs($this->checker);

        $request = Request::create(route('transaction.approve', $transaction->id), 'POST');

        Session::start();
        $request->setLaravelSession(app('session.store'));

        $response = $this->controller->approve($transaction->id);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertTrue(Session::has('successMessage'));

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'approved',
        ]);
        $this->assertDatabaseHas('wallets', [
            'user_id' => $this->maker->id,
            'balance' => 100,
        ]);
        $this->assertDatabaseHas('system_pool_balances', [
            'pool_balance' => $systemPoolBalance->pool_balance-100,
        ]);
    }

    public function test_reject_transaction_method()
    {
        $transaction = Transaction::create([
            'user_id' => $this->maker->id,
            'type' => 'credit',
            'amount' => 100,
            'status' => 'pending',
        ]);

        $this->actingAs($this->checker);

        $request = Request::create(route('transaction.reject', $transaction->id), 'POST');

        Session::start();
        $request->setLaravelSession(app('session.store'));

        $response = $this->controller->reject($transaction->id);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertTrue(Session::has('successMessage'));
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'rejected',
        ]);
    }

    public function test_find_transaction_method()
    {
        $this->actingAs($this->maker);

        $transaction = Transaction::create([
            'user_id' => $this->maker->id,
            'type' => 'credit',
            'amount' => 100,
            'status' => 'pending',
        ]);

        $foundTransaction = $this->controller->find($transaction->id);

        $this->assertNotNull($foundTransaction);
        $this->assertEquals($transaction->id, $foundTransaction->id);
    }

}
