<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException as Error;

class NewsletterController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        try {
            $validated = $request->validate([
                'email' => 'required|email|unique:newsletters|email:rfc,dns',
                'name' => 'nullable|string',
                'terms' => 'required|accepted',
                'privacy' => 'required|accepted',
            ]);
    
            $newsletter = Newsletter::create([
                'email' => $validated['email'],
                'name' => $validated['name'] ?? 'User',
                'terms_accepted' => true,
                'terms_accepted_at' => now(),
                'privacy_accepted' => true,
                'privacy_accepted_at' => now(),
                'consent_ip' => $request->ip(),
                'consent_user_agent' => $request->userAgent(),
            ]);
    
            Log::channel('newsletter')->info('Nuova iscrizione newsletter', [
                'email' => $validated['email'],
                'name' => $validated['name'],
                'ip' => $request->ip(),
                'user_agent' => substr($request->userAgent(), 0, 255),
                'metadata' => $newsletter->only(['terms_accepted_at', 'privacy_accepted_at'])
            ]);
    
            return response()->json("Ti sei iscritto alla newsletter di " . config('app.name'), 201);
        }catch (Error $e) {

            $firstError = $e->validator->errors()->first();

            return response()->json([
                'message' => 'Errore di validazione',
                'error' => $firstError,
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $email)
    {
        $request->merge(['email' => $email]);
        $request->validate([
            'email' => 'required|email'
        ]);
    
        $subscriber = Newsletter::where('email', $email)->first();
        
        // Se non esiste
        if (!$subscriber) {
            return response()->json('Email non trovata nella newsletter', 404);
        }
    
        $subscriber->delete();
    
        Log::info("Email cancellata dalla newsletter: ", [
            'email' => $subscriber->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        return response()->json('Disiscrizione a ' . config('app.name') . ' avvenuta con successo', 200);
    }
}
