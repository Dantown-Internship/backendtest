<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Exception;

class UserController extends Controller
{
    //
    private function createUser($data)
    {
        try {
            $user = User::create(array_merge($data, [
                'password' => Hash::make($data['password']),
                'email_verified_at' => now()
            ]));
            Wallet::create(['user_id' => $user->id, 'balance' => 0]);
            Auth::attempt([
                'email' => $data['email'],
                'password' => $data['password']
            ]);
            return redirect('dashboard')->with('success', 'Registration successful');
        } catch (Exception $ex) {
            return redirect('auth/signup')->with('error', $ex->getMessage());
        }

    }
    public function signUpView()
    {
        return view('auth.register');
    }
    public function checkerSignUpView()
    {
        return view('auth.register_checker');
    }
    public function signUp(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:5',
        ]);
        return $this->createUser(array_merge($request->all(), ['is_maker' => true]));
    }
    public function checkerSignup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:5',
        ]);
        return $this->createUser(array_merge($request->all(), ['is_checker' => true]));
    }
    public function loginView()
    {
        return view('auth.login');
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:5',
        ]);
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()
                ->intended('dashboard')
                ->withSuccess('Signed in');
        }
        return back()->with('error', 'Email or password  incorrect');
    }


    public function dashboard()
    {
        if (Auth::check()) {
            if (Auth::user()->is_maker) {
                $transactions = Transaction::where('wallet_id', Auth::user()->wallet->id)->orderBy('created_at', 'desc')->get();
            } else {
                $transactions = Transaction::orderBy('created_at', 'desc')->get();
            }

            $users = User::all();
            return view('user.dashboard', compact(['transactions', 'users']));
        }

        return redirect("login")->with('success', 'You are not allowed to access');
    }

    public function signOut()
    {
        Session::flush();
        Auth::logout();

        return redirect('auth/login');
    }
}