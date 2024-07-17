<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Logs\LogsController;
use App\Http\Controllers\Transactions\TransactionController;
use App\Http\Controllers\Notification\NotificationController;


Route::get('/', function () {
    return view('welcome');
});


Route::middleware('auth')->prefix('user')->group(function () {
    Route::get('dashboard', [TransactionController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
    Route::resource('transaction', TransactionController::class);
    Route::post('transaction/approve/{id}', [TransactionController::class,'approve'])->name('transaction.approve');
    Route::post('transaction/reject/{id}', [TransactionController::class,'reject'])->name('transaction.reject');

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('notification/read/{id}', [NotificationController::class, 'update'])->name('notification.read');
    Route::get('logs', [LogsController::class, 'index'])->name('users.logs');

});

require __DIR__.'/auth.php';
