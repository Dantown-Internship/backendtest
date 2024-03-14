<?php

namespace App\Providers;

use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use App\Models\Transaction;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('edit-transaction', function ($user, Transaction $transaction) {
            return $transaction->status === config('constant.status.rejected') ? Response::allow() : Response::deny('Transaction cannot be edited because it is already approved or pending.');
        });
        
        Gate::define('review-transaction', function ($user, Transaction $transaction) {
            return $transaction->status === config('constant.status.pending') ? Response::allow() : Response::deny('Transaction cannot be reviewed because it is already approved or rejected.');
        });
    }
}
