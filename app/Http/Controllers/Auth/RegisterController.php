<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|unique:users|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'password' => 'required|string|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

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
            'status' => 'pending'
        ]);

        // Send verification emails/SMS (implement this later)
        // $user->sendEmailVerificationNotification();
        // $user->sendPhoneVerificationNotification();

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }
}
