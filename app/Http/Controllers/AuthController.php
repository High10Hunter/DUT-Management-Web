<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    public function changePassword()
    {
        return view('auth.change-password');
    }

    public function storeNewPassword(ChangePasswordRequest $request)
    {
        $userId = $request->safe()->user_id;
        $newPassword = $request->safe()->password;
        try {
            User::where('id', $userId)
                ->update([
                    'password' => Hash::make($newPassword),
                ]);

            return response()->json(
                [
                    'status' => true,
                    'message' => 'Đổi mật khẩu thành công, vui lòng đăng nhập lại',
                ]
            );
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 400);
        }
    }
}
