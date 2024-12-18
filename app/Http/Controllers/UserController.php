<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\User;

class UserController extends Controller
{

    public function registerMethod(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        return redirect()->route('users.login')->with('success', 'Registration successful! Please login.');
    }

    public function loginMethod(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
            return redirect()->intended(route('files.list'))->with('success', 'Logged in');
        }

        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ]);
    }

    public function logoutMethod(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('users.login')->with(['flash' => 'Logged out']);
    }

    public function register()
    {
        return view('pages.users.register', ['isAuthenticated' => Auth::check()]);
    }

    public function login()
    {
        if(Auth::check()) {
            return redirect()->route('home');
        }
        return view('pages.users.login', []);
    }

    public function logout(Request $request)
    {
        return redirect()->route('users.logout.method');
    }

    public function me(Request $request) {
        $user = User::find(auth()->id());

        return view('pages.users.me', [
            'user' => $user,
            'isAuthenticated' => Auth::check(),
            'currentDir' => '/',
        ]);
    }
}