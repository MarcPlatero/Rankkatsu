<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ranking;
use App\Models\RankingImage;
use App\Models\RankingOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ModerationController extends Controller
{
    public function dashboard()
    {
        $rankings = Ranking::where('is_approved', false)
            ->with('user')
            ->latest()
            ->get();

        return inertia('Admin/Moderation/Dashboard', [
            'rankings' => $rankings,
        ]);
    }

    public function show(Ranking $ranking)
    {
        $ranking->load(['user', 'options', 'options.votes']);
        return inertia('Admin/Moderation/RankingReview', [
            'ranking' => $ranking
        ]);
    }

    public function approveRanking(Ranking $ranking)
    {
        $ranking->update(['is_approved' => true]);

        // Tornem a carregar la llista actualitzada
        $rankings = Ranking::where('is_approved', false)
            ->with('user')
            ->latest()
            ->get();

        return inertia('Admin/Moderation/Dashboard', [
            'rankings' => $rankings,
            'success' => 'Rànquing aprovat correctament.',
        ]);
    }

    public function rejectRanking(Ranking $ranking)
    {
        // Elimina el rànquing (i les seves opcions)
        $ranking->options()->delete();
        $ranking->delete();

        return redirect()
            ->route('admin.moderation.dashboard')
            ->with('success', 'Rànquing eliminat correctament.');
    }

    public function approveImage(RankingImage $image)
    {
        $image->update(['is_approved' => true]);
        return response()->json(['success' => true]);
    }

    public function rejectImage(RankingImage $image)
    {
        $image->delete();
        return response()->json(['success' => true]);
    }
}