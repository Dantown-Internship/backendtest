<?php

use App\Http\Controllers\Web\Checker\TransactionController as CheckerTransactionController;
use App\Http\Controllers\Web\Marker\TransactionController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function () {

    Route::get('/transactions', [ TransactionController::class, 'index'] )->name('transactions.index');
    Route::post('/transaction/create', [ TransactionController::class, 'store'] )->name('transaction.store');
    Route::patch('/transaction/update/{transactionId}', [ TransactionController::class, 'update'] )->name('transaction.update');

    Route::patch('/transaction/approve/{transactionId}', [ CheckerTransactionController::class, 'approve'] )->name('transaction.approve');
    Route::patch('/transaction/reject/{transactionId}', [ CheckerTransactionController::class, 'reject'] )->name('transaction.reject');

});

require __DIR__.'/auth.php';
