<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        if (auth()->check()) {
            $role = getRoleByValue(auth()->user()->role);
            return redirect()->route("$role.index");
        } else {
            return view('auth.login');
        }
    }

    public function logining(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['bail', 'required'],
            'password' => ['required'],
        ]);

        //remember login
        $remember = (!empty($request->remember_me)) ? true : false;


        if (Auth::attempt($credentials, $remember)) {
            if (auth()->check()) {
                $role = getRoleByValue(auth()->user()->role);
                $request->session()->regenerate();
                return redirect()->route("$role.index");
            }
        } else {
            session()->put('error', 'Tài khoản hoặc mật khẩu sai!');
            return redirect()->route('login');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();

        return redirect()->route('login');
    }
}
