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
use Illuminate\Support\Str;

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
            'slug' => 'nullable|string|unique:users,slug',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['nullable', 'string', 'in:admin,editor,author'],
        ]);

        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
        $slug = $this->generateUniqueSlug($slug);


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'slug' => $slug,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);

        // Assegna un ruolo predefinito all'utente
        $role = $request->role ?? 'user';
        $user->assignRole($role);

        return redirect(RouteServiceProvider::HOME)->with('status', 'Utente creato con successo');

    }

    /**
     * Genera uno slug univoco per l'utente.
    */
    private function generateUniqueSlug(string $base_slug): string
    {
        $slug = $base_slug;
        $n = 0;

        while (User::where('slug', $slug)->exists()) {
            $n++;
            $slug = "{$base_slug}-{$n}";
        }

        return $slug;
    }
}
