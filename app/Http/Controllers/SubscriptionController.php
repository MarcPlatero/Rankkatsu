<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SubscriptionController extends Controller
{
    // Mostra la pàgina de vendes
    public function index()
    {
        return Inertia::render('Premium/PremiumIndex', [
            'isPremium' => Auth::user()->is_premium,
            'csrfToken' => csrf_token(),
        ]);
    }

    // Redirigeix a la passarel·la de pagament
    public function create(Request $request)
    {
        $user = $request->user();
        
        $request->validate([
            'type' => 'required|in:monthly,lifetime'
        ]);
        
        $type = $request->input('type');

        if ($user->is_premium) {
            return back()->with('error', 'Ja ets membre PRO!');
        }

        // Mensual
        if ($type === 'monthly') {
            if (!env('STRIPE_MONTHLY_ID')) return back()->with('error', 'Error config mensual');

            return $user->newSubscription('default', env('STRIPE_MONTHLY_ID'))
                ->checkout([
                    'success_url' => route('premium.success'),
                    'cancel_url' => route('premium.index'),
                ]);
        }

        // Lifetime
        if ($type === 'lifetime') {
            if (!env('STRIPE_LIFETIME_ID')) return back()->with('error', 'Error config lifetime');

            return $user->checkout(env('STRIPE_LIFETIME_ID'), [
                'success_url' => route('premium.success'),
                'cancel_url' => route('premium.index'),
            ]);
        }
    }

    // Quan l'usuari torna després de pagar
    public function success(Request $request)
    {
        // Activem el flag is_premium (per si és Lifetime)
        // Si és mensual, Cashier ja ho gestiona.
        $request->user()->update(['is_premium' => true]);

        return Inertia::render('Premium/Success');
    }

    // Gestionar Subscripció (Portal de Client)
    public function portal(Request $request)
    {
        $user = $request->user();

        // Si és Premium però no té subscripció activa a Stripe, és Lifetime.
        if ($user->is_premium && !$user->subscribed('default')) {
            return back()->with('error', 'Tens un pla de per vida (Lifetime). No necessites gestionar cap subscripció.');
        }

        // Si no està subscrit de cap manera.
        if (!$user->subscribed('default')) {
             return back()->with('error', 'No tens cap subscripció activa per gestionar.');
        }

        // Redirigeix al portal de Stripe
        return $user->redirectToBillingPortal(route('premium.index'));
    }
}