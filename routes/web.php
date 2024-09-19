<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\CheckRole;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');;
    Route::get('/wallet', [WalletController::class, 'show'])->name('wallet.show');
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store']);
    
    Route::middleware([CheckRole::class.':checker'])->group(function () {
        Route::get('/transactions/pending', [TransactionController::class, 'pending'])->name('transactions.pending');
        Route::post('/transactions/{id}/approve', [TransactionController::class, 'approve'])->name('transactions.approve');
        Route::post('/transactions/{id}/reject', [TransactionController::class, 'reject'])->name('transactions.reject');
    });
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
