<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WalletController;
use App\Models\SystemPool;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome')->with('system_pool', SystemPool::where('name', config('constant.system_pool.name'))->first());
})->middleware(['auth', 'verified'])->name('home');

Route::get('/wallet', function () {
    return view('wallet')->with('user', auth()->user());
})->middleware(['auth', 'verified'])->name('wallet');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet');

    Route::get('/transaction', [TransactionController::class, 'index'])->name('transaction');
});

Route::middleware('maker')->group(function () {
    Route::get('/transaction/create', [TransactionController::class, 'create'])->name('transaction.create');
    Route::post('/transaction/create', [TransactionController::class, 'store'])->name('transaction.store');
    Route::get('/transaction/edit/{transaction}', [TransactionController::class, 'edit'])->name('transaction.edit');
    Route::put('/transaction/edit/{transaction}', [TransactionController::class, 'update'])->name('transaction.update');
});

Route::middleware('checker')->group(function () {
    Route::get('/transaction/review/{transaction}', [TransactionController::class, 'review'])->name('transaction.review');
    Route::post('/transaction/review/{transaction}', [TransactionController::class, 'decide'])->name('transaction.review');
});

require __DIR__.'/auth.php';
