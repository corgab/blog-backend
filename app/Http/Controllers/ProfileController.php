<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Str;
use App\Models\User;    

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validatedData = $request->validated();

        // Se c'è un nuovo nome
        if(isset($validatedData['name'])) {

            // Genera lo slug
            $newSlug = $slug = Str::slug($validatedData['name'], '-');

            // Se lo slug è diverso 
            if($user->slug !== $newSlug) {
                // Togli slug duplicati
                $validatedData['slug'] = $this->generateUniqueSlug($newSlug);
            }
        }

        // Aggiorna
        $user->fill($validatedData);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
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
