<?php

use App\Actions\TransactionActions;
use App\Exceptions\TransactionAlreadyApprovedException;
use App\Features\Transactions\ApproveTransactionFeature;
use App\Features\Transactions\CreateTransactionFeature;
use App\Features\Transactions\RejectTransactionFeature;
use App\Features\Transactions\UpdateTransactionFeature;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

pest()->extend(Tests\TestCase::class);

beforeEach( function() {

    $this->artisan('migrate:fresh --seed');
});

test('can create transaction feature', function(){


    $params = [
        'amount' => 1000,
        'description' => 'Blah',
        'type' => 'debit',
        'user' => User::find(2)
    ];

    ( new CreateTransactionFeature )->handle( new TransactionActions, $params );

    $this->assertDatabaseCount('transactions', 2);

    

});


test('can approve transaction feature', function(){

    ( new ApproveTransactionFeature( 1 ) )->handle( new TransactionActions, ['user' => User::find(1) ] );

    $this->assertDatabaseCount('transactions', 1);


});


test('can reject transaction feature', function(){

    ( new RejectTransactionFeature( 1 ) )->handle( new TransactionActions, ['user' => User::find(1) ] );

    $this->assertDatabaseCount('transactions', 1);


});