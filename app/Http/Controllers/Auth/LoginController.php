<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $user = User::find(Auth::id());
            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'message' => 'Успешная авторизация',
                'user' => $user,
                'token' => $token
            ]);
        }

        return response()->json([
            'message' => 'Неверные учетные данные'
        ], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        
        return response()->json([
            'message' => 'Успешный выход из системы'
        ]);
    }
}
