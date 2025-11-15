<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->profile_photo_path = $request->validated('avatar');

        $user->save();

        return redirect()->route('profile.show')->with('success', 'El teu perfil s’ha actualitzat correctament!');
    }

    /**
     * Delete the user's account.
     */
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

        // Afegim is_favorite a tots
        $favoriteIds = $user->favoriteRankings->pluck('id')->toArray();

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
