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

// Mail
use Illuminate\Support\Facades\Mail;
use App\Mail\UserInfo;





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

            $role = 'user';
            $user->assignRole($role);
            
            // Creazione di un token per l'utente appena registrato
            $token = $user->createToken('Frontend')->plainTextToken;

            Mail::to($user->email)->send(new UserInfo($user));
            
            return response()->json([
                'success' => __('Registrazione effettuata con successo'),
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

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('Frontend')->plainTextToken;

            return response()->json([
                'success' => 'Login effettuato con successo!',
                'token' => $token,
                'user' => $user,
            ]);
        }

        return response()->json(['errMessage' => 'Credenziali non valide.'], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete(); // Elimina il token attuale
        // $request->user()->tokens()->delete()

        return response()->json(['success' => 'Logout effettuato con successo!']);
    }


}
