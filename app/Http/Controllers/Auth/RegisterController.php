<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'phone' => 'nullable|string|unique:users|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ], [
                'name.required' => 'Имя обязательно для заполнения',
                'email.required' => 'Email обязателен для заполнения',
                'email.email' => 'Введите корректный email адрес',
                'email.unique' => 'Пользователь с таким email уже существует',
                'password.required' => 'Пароль обязателен для заполнения',
                'password.min' => 'Пароль должен быть не менее 8 символов',
                'phone.unique' => 'Этот номер телефона уже используется',
                'phone.regex' => 'Неверный формат номера телефона'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Ошибка валидации',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Логируем входящие данные (без пароля)
            Log::info('Попытка регистрации:', [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone
            ]);

            // Handle avatar upload if present
            $avatarPath = null;
            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'avatar' => $avatarPath,
                'status' => 'active',
                'role' => 'user'
            ]);

            // Создаем токен для нового пользователя
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Регистрация успешна',
                'user' => $user,
                'token' => $token
            ], 201);

        } catch (\Exception $e) {
            Log::error('Ошибка при регистрации:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Ошибка при регистрации',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
