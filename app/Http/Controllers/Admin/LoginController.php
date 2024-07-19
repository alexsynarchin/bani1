<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required'],
            'password' => ['required'],
        ]);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return route('admin.home');
            //return redirect()->intended('dashboard');
        }

        throw ValidationException::withMessages([
            'login' => [trans('auth.failed')],
        ]);
    }
    public function showLogin()
    {
        return view('admin.auth.login');
    }
}
