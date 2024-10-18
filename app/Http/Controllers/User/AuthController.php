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
use Illuminate\Auth\Events\Registered; 
use Illuminate\Support\Str;

// Mail
use Illuminate\Support\Facades\Mail;
use App\Mail\Welcome;

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
                'email_verified_at' => null,
            ]);
    
            $role = 'user';
            $user->assignRole($role);
    
            // Generazione di un token di verifica dell'email
            $verificationToken = Hash::make($user->email . $user->created_at);
    
            // Invio dell'email di benvenuto con il link di verifica
            Mail::to($user->email)->send(new Welcome($user, $verificationToken));
            
            return response()->json([
                'success' => __('Registrazione effettuata con successo. Controlla la tua email per confermare il tuo account.'),
                'user' => $user,
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

    public function verifyEmail(Request $request)
    {
        $email = $request->email;
        $token = $request->token;

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Utente non trovato.'], 404);
        }

        $expectedToken = Hash::make($user->email . $user->created_at);

        // Verifica il token
        if (!Hash::check($user->email . $user->created_at, $request->token)) {
            return response()->json(['success' => false, 'message' => 'Token di verifica non valido.'], 403);
        }
        
       
        $user->email_verified_at = now();
        $user->save();

        return response()->json(['success' => true, 'message' => 'Email verificata con successo!']);

    }




}
