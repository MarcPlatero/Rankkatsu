<?php

namespace App\Http\Controllers;

use App\Models\Ranking;
use App\Models\RankingOption;
use App\Models\RankingVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RankingVoteController extends Controller
{
    // Votar
    public function vote(Request $request, Ranking $ranking)
    {
        $request->validate([
            'option_id' => 'required|exists:ranking_options,id',
        ]);

        $option = RankingOption::where('ranking_id', $ranking->id)
            ->findOrFail($request->option_id);

        // Esborra el vot previ de l’usuari (per evitar duplicats)
        RankingVote::where('user_id', Auth::id())
            ->whereHas('option', function ($q) use ($ranking) {
                $q->where('ranking_id', $ranking->id);
            })
            ->delete();

        // Desa el nou vot
        RankingVote::create([
            'ranking_option_id' => $option->id,
            'user_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Has votat correctament!');
    }

    // Treure vot
    public function unvote(Ranking $ranking)
    {
        RankingVote::where('user_id', Auth::id())
            ->whereHas('option', function ($q) use ($ranking) {
                $q->where('ranking_id', $ranking->id);
            })
            ->delete();

        return redirect()->back()->with('success', 'Has eliminat el teu vot!');
    }
}
