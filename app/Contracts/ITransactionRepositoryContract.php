<?php

namespace App\Contracts;

use App\Models\Transaction;

interface ITransactionRepositoryContract {

    public function createTrasaction( array $params, int $userId ) : Transaction;

    public function updateTransaction( Transaction $transaction, array $params ) : Transaction;
    
    public function approveTransaction( Transaction $trasaction ) : Transaction;

    public function rejectTransaction( Transaction $transaction ) : Transaction;

    public function getTransactions( array $params = [] ) : mixed;

    public function getTransaction( int $transactionId ) : Transaction;

    public function getUserTransactions( int $userId, array $param = [] ) : mixed;

}