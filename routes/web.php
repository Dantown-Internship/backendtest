<?php

use App\Http\Controllers\TransactionsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});
Route::prefix('auth')->group(function () {
    Route::get('login', [UserController::class, 'loginView'])->name('login');
    Route::post('login', [UserController::class, 'login'])->name('login');
    Route::get('signup', [UserController::class, 'signUpView'])->name('register');
    Route::post('signup', [UserController::class, 'signUp'])->name('register');
    Route::get('checker_signup', [UserController::class, 'checkerSignUpView'])->name('checkerSignup');
    Route::post('checker_signup', [UserController::class, 'checkerSignup'])->name('checkerSignup');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::post('transaction', [TransactionsController::class, 'create'])->name('transaction');
    Route::post('logout', [UserController::class, 'signOut'])->name('logout');

    // Transaction
    Route::group(['middleware' => 'isMaker', 'prefix' => 'transaction'], function () {
        Route::post('new', [TransactionsController::class, 'create'])->name('new_transaction');
    });

    Route::group(['middleware' => 'isChecker', 'prefix' => 'transaction'], function () {
        Route::post('{transactionId}/approve', [TransactionsController::class, 'approve'])->name('approve_transaction');
        Route::post('{transactionId}/reject', [TransactionsController::class, 'reject'])->name('reject_transaction');
    });
});

Route::get('/home', [HomeController::class, 'index'])->name('home');