<?php

namespace App\Providers;

use App\Models\TransactionActivity;
use App\Models\Wallet;
use App\Notifications\TransactionApproved;
use App\Notifications\TransactionRejected;

class PerformPostTransactionUpdatedTasks
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TransactionUpdated $event): void
    {
        $status = $event->status;
        $transaction = $event->transaction;

        if ($status === 'approved') {
            if ($transaction->type === 'deposit') {
                $transaction->user->wallet->addToWallet($transaction->amount);
                $transaction->user->wallet->save();

                $admin_wallet = Wallet::find(1);
                $admin_wallet->addToWallet($transaction->amount);
                $admin_wallet->save();

                $transaction->user->notify(new TransactionApproved($transaction));
            } else {
                $transaction->user->wallet->reduceFromWallet($transaction->amount);
                $transaction->user->wallet->save();

                $admin_wallet = Wallet::find(1);
                $admin_wallet->reduceFromWallet($transaction->amount);
                $admin_wallet->save();

                $transaction->user->notify(new TransactionApproved($transaction));
            }
        } else {
            $transaction->user->notify(new TransactionRejected($transaction));
        }

        $amount = number_format($transaction->amount, 2);
        $name = $transaction->user->name;

        TransactionActivity::create([
            'transaction_id' => $transaction->id,
            'activity' => "$name $transaction->type transaction of NGN $amount was $status",
        ]);
    }
}
