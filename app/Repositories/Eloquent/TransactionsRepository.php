<?php

namespace App\Repositories\Eloquent;

use App\Contracts\ITransactionRepositoryContract;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class TransactionsRepository implements ITransactionRepositoryContract {

    public function createTrasaction( array $params, int $userId ) : Transaction
    {
        return Transaction::create( $params + ['user_id' => $userId ] );
    }

    public function updateTransaction( Transaction $transaction, array $params ) : Transaction
    {
        $transaction->update( ['description' => $params['description'], 'approval_status' => 'pending' ] );

        return $transaction;
    }
    
    public function approveTransaction( Transaction $transaction ) : Transaction
    {
        $transaction->update( ['approval_status' => 'approved'] );
        
        return $transaction;
    }

    public function rejectTransaction( Transaction $transaction ) : Transaction
    {
        $transaction->update( ['approval_status' => 'rejected'] );

        return $transaction;
    }

    public function getTransactions( array $params = [] ) : Collection
    {
        return Transaction::get();
    }

    public function getTransaction( int $transactionId ) : Transaction
    {
        return Transaction::find( $transactionId );

    }

    public function getUserTransactions( int $userId, array $param = [] ) : Collection
    {
        return User::find( $userId )->transactions()->get();
    }

}