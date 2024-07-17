<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\NewCheckerAccountCreated;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Create a new checker account.
     *
     * @param  \Illuminate\Http\Request  $request  The HTTP request object.
     * @return \Illuminate\Http\RedirectResponse The redirect response.
     */
    public function create(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'checker',
            'password' => Hash::make('password'),
        ]);

        $user->notify(new NewCheckerAccountCreated());

        return redirect()->back()->with('success', 'Checker account created successfully');
    }
}
