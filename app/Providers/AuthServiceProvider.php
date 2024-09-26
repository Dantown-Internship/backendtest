<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
   
    public function boot(): void
    {
        Gate::define('checker-actions', fn() => auth()->user()->is_checker );
        Gate::define('marker-actions', fn() => auth()->user()->is_marker );
    }
}
