<?php

use App\Actions\TransactionActions;
use App\Exceptions\TransactionIsPendingException;
use App\Features\Transactions\UpdateTransactionFeature;
use App\Models\Role;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    $this->seed();
});

test('can see transactions page', function(){


    $user = User::firstWhere('role_id', Role::firstWhere('name', 'marker')->id );

    $response = $this->actingAs($user)
                    ->get('/transactions');

    
    $response->assertOk();
});


test('can create a transaction', function(){

    $user = User::firstWhere('role_id', Role::firstWhere('name', 'marker')->id );

    $response = $this->actingAs($user)
                    ->post('/transaction/create', [
                        'amount' => 1000,
                        'type' => 'credit',
                        'description' => 'Test Transaction'
                    ]);

    
    $response->assertStatus(302);
    $response->assertRedirect('/transactions');
    
    $this->assertDatabaseCount('transactions', 2);
});

test('cannot create a transaction if role is not marker', function(){

    $user = User::firstWhere('role_id', Role::firstWhere('name', 'checker')->id );

    $response = $this->actingAs($user)
                    ->post('/transaction/create', [
                        'amount' => 1000,
                        'type' => 'credit',
                        'description' => 'Test Transaction'
                    ]);

    
    $response->assertStatus(403);
    
    $this->assertDatabaseCount('transactions', 1);

});


test('cannot update if transaction is pending', function(){

    $user = User::firstWhere('role_id', Role::firstWhere('name', 'marker')->id );

    $transaction = DB::table('transactions')->insert([
        'id' => 2,
        'amount' => 1000,
        'description' => 'Blah',
        'type' => 'debit',
        'user_id' => $user->id
    ]);


    $response = $this->actingAs($user)
                    ->patch('/transaction/update/2', [
                        'description' => 'Updated Transaction'
                    ]);

        
    $response->assertStatus(403);

    $transaction = Transaction::find(2);

    $this->assertSame(1000.0, $transaction->amount);
    $this->assertSame('debit', $transaction->type);
    $this->assertSame('Blah', $transaction->description);

    $this->assertDatabaseCount('transactions', 2);
});


test('cannot update if transaction is approved', function(){

    $user = User::firstWhere('role_id', Role::firstWhere('name', 'marker')->id );

    $transaction = DB::table('transactions')->insert([
        'id' => 2,
        'amount' => 1000,
        'description' => 'Blah',
        'type' => 'debit',
        'user_id' => $user->id
    ]);

    $transaction = Transaction::find(2);

    $transaction->update(['approval_status' => 'approved']);

    $response = $this->actingAs($user)
                    ->patch('/transaction/update/2', [
                        'description' => 'Updated Transaction'
                    ]);


    $response->assertStatus(403);

    $transaction->refresh();

    $this->assertSame(1000.0, $transaction->amount);
    $this->assertSame('debit', $transaction->type);
    $this->assertSame('Blah', $transaction->description);

    $this->assertDatabaseCount('transactions', 2);
});


test('can update if transaction is rejected', function(){

    $user = User::firstWhere('role_id', Role::firstWhere('name', 'marker')->id );

    $transaction = DB::table('transactions')->insert([
        'id' => 2,
        'amount' => 1000,
        'description' => 'Blah',
        'type' => 'debit',
        'user_id' => $user->id
    ]);

    $transaction = Transaction::find(2);

    $transaction->update(['approval_status' => 'rejected']);
        
    $response = $this->actingAs($user)
                    ->patch('/transaction/update/2', [
                        'description' => 'Updated Transaction'
                    ]);

   
    $response->assertStatus(302);
    $response->assertRedirect('/transactions');
   
    $transaction->refresh();

    $this->assertSame(1000.0, $transaction->amount);
    $this->assertSame('debit', $transaction->type);
    $this->assertSame('Updated Transaction', $transaction->description);
    $this->assertDatabaseCount('transactions', 2);
});



test('cannot approve transaction if role is not checker', function(){

    $user = User::firstWhere('role_id', Role::firstWhere('name', 'marker')->id );

    $transaction = DB::table('transactions')->insert([
        'id' => 2,
        'amount' => 1000,
        'description' => 'Blah',
        'type' => 'debit',
        'user_id' => $user->id
    ]);

    $response = $this->actingAs($user)
                     ->patch('/transaction/approve/2');

   
    $response->assertStatus(403);

    $transaction = Transaction::find(2);

    $this->assertSame(1000.0, $transaction->amount);
    $this->assertSame('debit', $transaction->type);
    $this->assertSame('Blah', $transaction->description);
    $this->assertSame('pending', $transaction->approval_status);

    $this->assertDatabaseCount('transactions', 2);
});


test('can approve transaction if role is checker', function(){

    $user = User::firstWhere('role_id', Role::firstWhere('name', 'marker')->id );
    $user2 = User::firstWhere('role_id', Role::firstWhere('name', 'checker')->id );

    $transaction = DB::table('transactions')->insert([
        'id' => 2,
        'amount' => 1000,
        'description' => 'Blah',
        'type' => 'debit',
        'user_id' => $user->id
    ]);

    $response = $this->actingAs($user2)
                     ->patch('/transaction/approve/2');

   
    $response->assertStatus(302);

    $transaction = Transaction::find(2);
   
    $this->assertSame(1000.0, $transaction->amount);
    $this->assertSame('debit', $transaction->type);
    $this->assertSame('Blah', $transaction->description);
    $this->assertSame('approved', $transaction->approval_status);

    $this->assertDatabaseCount('transactions', 2);
});


test('can reject transaction if role is checker', function(){

    $user = User::firstWhere('role_id', Role::firstWhere('name', 'marker')->id );
    $user2 = User::firstWhere('role_id', Role::firstWhere('name', 'checker')->id );

    $transaction = DB::table('transactions')->insert([
        'id' => 2,
        'amount' => 1000,
        'description' => 'Blah',
        'type' => 'debit',
        'user_id' => $user->id
    ]);

    $response = $this->actingAs($user2)
                     ->patch('/transaction/reject/2');

   
    $response->assertStatus(302);

    $transaction = Transaction::find(2);
   
    $this->assertSame(1000.0, $transaction->amount);
    $this->assertSame('debit', $transaction->type);
    $this->assertSame('Blah', $transaction->description);
    $this->assertSame('rejected', $transaction->approval_status);

    $this->assertDatabaseCount('transactions', 2);
});

test('cannot reject transaction if role is not checker', function(){

    $user = User::firstWhere('role_id', Role::firstWhere('name', 'marker')->id );

    $transaction = DB::table('transactions')->insert([
        'id' => 2,
        'amount' => 1000,
        'description' => 'Blah',
        'type' => 'debit',
        'user_id' => $user->id
    ]);

    $response = $this->actingAs($user)
                     ->patch('/transaction/reject/2');

   
    $response->assertStatus(403);

    $transaction = Transaction::find(2);
   
    $this->assertSame(1000.0, $transaction->amount);
    $this->assertSame('debit', $transaction->type);
    $this->assertSame('Blah', $transaction->description);
    $this->assertSame('pending', $transaction->approval_status);

    $this->assertDatabaseCount('transactions', 2);
});