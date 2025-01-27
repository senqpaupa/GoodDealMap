<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if (!$userId = Auth::id()) {
            return response()->json(['message' => 'Пользователь не авторизован'], 401);
        }

        if ($request->hasFile('avatar')) {
            $user = User::find($userId);
            
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            
            User::where('id', $userId)->update(['avatar' => $path]);

            return response()->json([
                'message' => 'Аватар успешно обновлен',
                'avatar' => Storage::url($path)
            ]);
        }

        return response()->json([
            'message' => 'Файл не был загружен'
        ], 400);
    }

    public function show(Request $request)
    {
        if (!$userId = Auth::id()) {
            return response()->json(['message' => 'Пользователь не авторизован'], 401);
        }
        
        $user = User::find($userId);
        
        if ($user->avatar) {
            $user->avatar = Storage::url($user->avatar);
        }
        
        return response()->json($user);
    }

    public function updateProfile(Request $request)
    {
        if (!$userId = Auth::id()) {
            return response()->json(['message' => 'Пользователь не авторизован'], 401);
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'age' => 'sometimes|integer|min:1|max:150',
            'phone' => 'sometimes|string|max:20',
            'email' => ['sometimes', 'email', Rule::unique('users')->ignore($userId)],
            'about' => 'sometimes|string|max:1000',
        ]);

        $user = User::find($userId);
        
        // Обновляем только предоставленные поля
        if ($request->has('name')) $user->name = $request->name;
        if ($request->has('age')) $user->age = $request->age;
        if ($request->has('phone')) $user->phone = $request->phone;
        if ($request->has('email')) $user->email = $request->email;
        if ($request->has('about')) $user->about = $request->about;

        $user->save();

        return response()->json([
            'message' => 'Профиль успешно обновлен',
            'user' => $user
        ]);
    }
} 