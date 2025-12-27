<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($request->has('avatar')) {
            $avatarId = $request->input('avatar');

            $officialAvatars = config('avatars');

            if ($officialAvatars && array_key_exists($avatarId, $officialAvatars)) {
                
                $isPremiumAvatar = $officialAvatars[$avatarId];

                if ($isPremiumAvatar && !$user->is_premium) {
                    return back()->with('error', 'Aquest avatar és exclusiu per a usuaris Premium.');
                }

                $user->profile_photo_path = $avatarId;
            }
        }

        $user->save();

        return redirect()->route('profile.show')->with('success', 'Perfil actualitzat!');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function show(Request $request)
    {
        $user = $request->user();

        $rankings = $user->rankings()->withCount('comments')->get();
        
        $favoriteRankings = $user->favoriteRankings()->with('user')->get();
        $favoriteIds = $favoriteRankings->pluck('id')->toArray();

        foreach ($rankings as $ranking) {
            $ranking->is_favorite = in_array($ranking->id, $favoriteIds);
        }

        foreach ($favoriteRankings as $fav) {
            $fav->is_favorite = true;
        }

        return Inertia::render('Profile/ShowProfile', [
            'user' => $user,
            'rankings' => $rankings,
            'favorites' => $favoriteRankings,
        ]);
    }
}