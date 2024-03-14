<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

uses(RefreshDatabase::class);

it('displays transaction index view for maker', function () {
    $user = User::factory()->create();
    $user->wallet()->save((new Wallet));
    $this->actingAs($user);

    $response = $this->get(route('transaction'));

    $response->assertStatus(200)
             ->assertViewIs('transaction.index')
             ->assertViewHas('user', $user)
             ->assertViewHas('checker', config('constant.role.checker'))
             ->assertViewHas('maker', config('constant.role.maker'))
             ->assertViewHas('rejected', config('constant.status.rejected'))
             ->assertViewHas('pending', config('constant.status.pending'));
});

it('displays transaction index view for checker', function () {
    $user = User::factory()->create();
    $user->wallet()->save((new Wallet));
    $transaction = Transaction::create([
        'amount' => 100.00,
        'type' => 'Credit',
        'description' => 'Test create a transaction of 100.0 credit',
        'user_id' => $user->id,
        'third_party' => env('APP_NAME')
    ]);

    $user2 = User::factory()->create(['role' => config('constant.role.checker')]);
    $user2->wallet()->save((new Wallet));
    $this->actingAs($user2);

    $response = $this->get(route('transaction'));

    $response->assertStatus(200)
             ->assertViewIs('transaction.index')
             ->assertViewHas('transactions', Transaction::all())
             ->assertViewHas('user', $user2)
             ->assertViewHas('checker', config('constant.role.checker'))
             ->assertViewHas('maker', config('constant.role.maker'))
             ->assertViewHas('rejected', config('constant.status.rejected'))
             ->assertViewHas('pending', config('constant.status.pending'));
});

it('displays create transaction view for authenticated user', function () {
    $user = User::factory()->create();
    $user->wallet()->save((new Wallet));
    $this->actingAs($user);

    $response = $this->get(route('transaction.create'));

    $response->assertStatus(200)
             ->assertViewIs('transaction.create')
             ->assertViewHas('user', $user)
             ->assertViewHas('transaction_types', config('constant.transaction.type'));
});

it('stores a new transaction in storage', function () {
    $user = User::factory()->create();
    $user->wallet()->save((new Wallet));
    $this->actingAs($user);

    $response = $this->post(route('transaction.store'), [
        'amount' => 100.00,
        'type' => 'Credit',
        'description' => 'Test create a transaction of 100.0 credit',
        'user_id' => $user->id,
        'third_party' => env('APP_NAME')
    ]);
    

    $response->assertRedirect(route('transaction'))
             ->assertSessionHas('message', 'Your transaction will be reviewed soon');
});

it('displays edit transaction view for authorized user', function () {
    $user = User::factory()->create();
    $user->wallet()->save((new Wallet));
    $transaction = Transaction::create([
        'amount' => 100.00,
        'type' => 'Credit',
        'description' => 'Test create a transaction of 100.0 credit',
        'user_id' => $user->id,
        'third_party' => env('APP_NAME')
    ]);

    $user2 = User::factory()->create(['role' => config('constant.role.checker')]);
    $user2->wallet()->save((new Wallet));
    $this->actingAs($user2);

    $response = $this->post(route('transaction.review', $transaction), [
        'status' => config('constant.status.rejected'),
        'note' => 'Rejected',
    ]);
    
    $this->actingAs($user);

    $response = $this->get(route('transaction.edit', $transaction));

    $response->assertStatus(200)
             ->assertViewIs('transaction.edit')
             ->assertViewHas('user', $user)
             ->assertViewHas('transaction_types', config('constant.transaction.type'))
             ->assertViewHas('note', $transaction->note)
             ->assertViewHas('transaction', $transaction);
});

it('does not display edit transaction view for unauthorized user', function () {
    $user = User::factory()->create();
    $user->wallet()->save((new Wallet));
    $transaction = Transaction::create([
        'amount' => 100.00,
        'type' => 'Credit',
        'description' => 'Test create a transaction of 100.0 credit',
        'user_id' => $user->id,
        'third_party' => env('APP_NAME')
    ]);
    $this->actingAs($user);

    $response = $this->get(route('transaction.edit', $transaction));

    $response->assertStatus(403);
});

it('displays review transaction view for authorized user', function () {
    $user = User::factory()->create();
    $user->wallet()->save((new Wallet));
    $transaction = Transaction::create([
        'amount' => 100.00,
        'type' => 'Credit',
        'description' => 'Test create a transaction of 100.0 credit',
        'user_id' => $user->id,
        'third_party' => env('APP_NAME')
    ]);
    
    $user2 = User::factory()->create(['role' => config('constant.role.checker')]);
    $user2->wallet()->save((new Wallet));
    $this->actingAs($user2);

    $response = $this->get(route('transaction.review', $transaction));

    $response->assertStatus(200)
             ->assertViewIs('transaction.review')
             ->assertViewHas('user', $user2)
             ->assertViewHas('transaction', $transaction)
             ->assertViewHas('decisions', config('constant.status'));
});

it('does not display review transaction view for unauthorized user', function () {
    $user = User::factory()->create();
    $user->wallet()->save((new Wallet));
    $transaction = Transaction::create([
        'amount' => 100.00,
        'type' => 'Credit',
        'description' => 'Test create a transaction of 100.0 credit',
        'user_id' => $user->id,
        'third_party' => env('APP_NAME')
    ]);
    $this->actingAs($user);

    $response = $this->get(route('transaction.review', $transaction));

    $response->assertStatus(403);
});

it('decides transaction for authorized user', function() {
    $user = User::factory()->create();
    $user->wallet()->save((new Wallet));
    $transaction = Transaction::create([
        'amount' => 100.00,
        'type' => 'Credit',
        'description' => 'Test create a transaction of 100.0 credit',
        'user_id' => $user->id,
        'third_party' => env('APP_NAME')
    ]);

    $user2 = User::factory()->create(['role' => config('constant.role.checker')]);
    $user2->wallet()->save((new Wallet));
    $this->actingAs($user2);

    $response = $this->post(route('transaction.review', $transaction), [
        'status' => config('constant.status.rejected'),
        'note' => 'Rejected',
    ]);
    
    // $systemPool = SystemPool::create(['name' => config('constant.system_pool.name')]);
    $response->assertRedirect(route('transaction'));

});

it('does not decide transaction for authorized user', function() {
    $user = User::factory()->create();
    $user->wallet()->save((new Wallet));
    $transaction = Transaction::create([
        'amount' => 100.00,
        'type' => 'Credit',
        'description' => 'Test create a transaction of 100.0 credit',
        'user_id' => $user->id,
        'third_party' => env('APP_NAME')
    ]);
    $this->actingAs($user);$response = $this->post(route('transaction.review', $transaction), [
        'status' => config('constant.status.rejected'),
        'note' => 'Rejected',
    ]);

    $response->assertStatus(403);
});

it('updates transaction for authorized user', function () {
    $user = User::factory()->create();
    $user->wallet()->save((new Wallet));
    $transaction = Transaction::create([
        'amount' => 100.00,
        'type' => 'Credit',
        'description' => 'Test create a transaction of 100.0 credit',
        'user_id' => $user->id,
        'third_party' => env('APP_NAME')
    ]);

    $user2 = User::factory()->create(['role' => config('constant.role.checker')]);
    $user2->wallet()->save((new Wallet));
    $this->actingAs($user2);

    $response = $this->post(route('transaction.review', $transaction), [
        'status' => config('constant.status.rejected'),
        'note' => 'Rejected',
    ]);
    
    $this->actingAs($user);

    $response = $this->put(route('transaction.update', $transaction), [
        'amount' => 150.00,
        'type' => 'Debit',
        'description' => 'Updated description',
    ]);

    $response->assertRedirect(route('transaction'))
             ->assertSessionHas('message', 'Your transaction will be reviewed soon');
});

it('does not display update transaction view for unauthorized user', function () {
    $user = User::factory()->create();
    $user->wallet()->save((new Wallet));
    $transaction = Transaction::create([
        'amount' => 100.00,
        'type' => 'Credit',
        'description' => 'Test create a transaction of 100.0 credit',
        'user_id' => $user->id,
        'third_party' => env('APP_NAME')
    ]);
    $this->actingAs($user);

    $response = $this->put(route('transaction.update', $transaction), [
        'amount' => 150.00,
        'type' => 'Debit',
        'description' => 'Updated description',
    ]);

    $response->assertStatus(403);
});
