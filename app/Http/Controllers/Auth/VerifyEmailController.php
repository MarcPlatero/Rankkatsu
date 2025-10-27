<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        // Si l'usuari ja tenia el correu verificat
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('home') . '?verified=1');
        }

        // Si es pot marcar com verificat ara
        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        // Redirigeix a la pàgina principal amb el missatge de verificació
        return redirect()->intended(route('home') . '?verified=1');
    }
}
