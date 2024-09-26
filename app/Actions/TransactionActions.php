<?php

namespace App\Actions;

use App\Contracts\ITransactionRepositoryContract;
use App\Exceptions\TransactionAlreadyApprovedException;
use App\Exceptions\TransactionAlreadyRejectedException;
use App\Exceptions\TransactionIsPendingException;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TransactionActions extends BaseAction {

    private Transaction $transaction;

    private ITransactionRepositoryContract $transactionRepository;

    public function __construct()
    {
        $this->transactionRepository = app()->get( ITransactionRepositoryContract::class );
    }

    public function createTrasaction() : static
    {
        $params = $this->getParams();

        $this->setData( [ 
            'transaction' => $this->transactionRepository->createTrasaction( $params, $params['user']->id  )
        ]);

        return $this;
    }

    public function updateTrasaction( int $transactionId ) : static
    {
        $transaction = $this->getTransaction( $transactionId )->transaction;

        if( $transaction->is_approved ) throw new TransactionAlreadyApprovedException("Cannot Update. This transaction has already been approved");

        if( $transaction->is_pending ) throw new TransactionIsPendingException("Cannot Update. This transaction is still pending");
        

        $this->transactionRepository->updateTransaction( $transaction, $this->getParams() );

        return $this;
    }
    
    public function approveTransaction( int $transactionId ) : static
    {
    
        $transaction = $this->getTransaction( $transactionId )->transaction;

        if( $transaction->is_approved ) throw new TransactionAlreadyApprovedException("This transaction has already been approved");

        $this->transactionRepository->approveTransaction( $transaction );

        return $this;
    }

    public function rejectTransaction( int $transactionId ) : static
    {
        $transaction = $this->getTransaction( $transactionId )->transaction;

        if( $transaction->is_rejected ) throw new TransactionAlreadyRejectedException("This transaction has already been rejected");
       
        $this->transactionRepository->rejectTransaction( $transaction  );

        return $this;
    }

    public function getTransactions( ?User $user = null ) : static
    {
        if( $user && $user->is_marker ){

            $this->setData( [ 'transactions' => $this->transactionRepository->getUserTransactions( $user->id ) ] ) ;

        }else{

            $this->setData( [ 'transactions' => $this->transactionRepository->getTransactions() ] ) ;
        }

        return $this;
    }

    public function getTransaction( int $transactionId ) : static
    {
        $this->transaction = $this->validateTransaction( $this->transactionRepository->getTransaction( $transactionId ) );

        $this->setData( [ 'transaction' =>  $this->transaction ] );

        return $this;
    }


    public function validateTransaction( ?Transaction $transaction )
    {
        if( ! $transaction ) throw new ModelNotFoundException("Transaction Does Not Exist");

        return $transaction;

    }
    
}