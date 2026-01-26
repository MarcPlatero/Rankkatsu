<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'locale' => app()->getLocale(),

            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'profile_photo_path' => $request->user()->profile_photo_path,
                    'profile_photo_url' => $request->user()->profile_photo_url,
                    'is_admin' => (bool) $request->user()->is_admin,
                    'is_premium' => (bool) ($request->user()->is_premium || $request->user()->subscribed('default')),
                    'notifications' => $request->user()->notifications()
                        ->latest()
                        ->take(20)
                        ->get()
                        ->map(function ($n) {
                            return [
                                'id' => $n->id,
                                'data' => $n->data,
                                'created_at' => $n->created_at->diffForHumans(),
                                'read_at' => $n->read_at,
                            ];
                        }),
                    
                    'unread_count' => $request->user()->unreadNotifications()->count(),

                ] : null,
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
        ]);
    }
}