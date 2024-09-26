<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Http\Request;

class RedirectWhenAuthenticated extends RedirectIfAuthenticated
{
   protected function redirectTo(Request $request): ?string
   {
        return route('transactions.index');
   }
}
