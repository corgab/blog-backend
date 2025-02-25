<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\Welcome;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['nullable', 'string', 'in:admin,editor,author'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Invio mail
        // event(new Registered($user));

        // Mail::to($user->email)->send(new Welcome($user));

        // Assegna un ruolo predefinito all'utente
        $role = $request->role ?? 'user';
        $user->assignRole($role);

        // Se user ha ruolo user
        // if($user->hasRole('user')) {
            Auth::login($user);
        // }

        // return redirect()->route('verification.notice')->with('status', 'Utente creato con successo.');
        return redirect(RouteServiceProvider::HOME)->with('status', 'Utente creato con successo');


    }
}
