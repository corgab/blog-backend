<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;



class AuthController extends Controller
{

    public function register(Request $request)
    {
        try {
            
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'role' => ['nullable', 'string', 'in:admin,editor,author'],
            ]);

            // Creazione di un nuovo utente
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                
            ]);

            $role = 'admin';
            $user->assignRole($role);
            
            // Creazione di un token per l'utente appena registrato
            $token = $user->createToken('Frontend')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
            ], 201); 
        } catch (ValidationException $e) {
            return response()->json([
                'errMessage' => $e->validator->errors()->first(),
                'errors' => $e->validator->errors(),
            ], 422);
        }
    }

    public function login(LoginRequest $request) // Inserire Validazioni
    {

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            // Genera il token
            $token = $user->createToken('Frontend')->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => $user,
            ]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
