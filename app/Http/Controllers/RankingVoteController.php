<?php

namespace App\Http\Controllers;

use App\Models\Ranking;
use App\Models\RankingOption;
use App\Models\RankingVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\RankingInteraction;

class RankingVoteController extends Controller
{
    public function vote(Request $request, Ranking $ranking)
    {
        $request->validate([
            'option_id' => 'required|exists:ranking_options,id',
        ]);

        // Comprovar que l'opció pertany al ranking
        $option = RankingOption::where('id', $request->option_id)
            ->where('ranking_id', $ranking->id)
            ->firstOrFail();

        // Eliminar vot previ d'aquest usuari en aquest rànquing
        RankingVote::where('user_id', Auth::id())
            ->whereHas('option', fn($q) => $q->where('ranking_id', $ranking->id))
            ->delete();

        // Desa el nou vot
        RankingVote::create([
            'ranking_option_id' => $option->id,
            'user_id' => Auth::id(),
        ]);

        // Notificació
        if ($ranking->user_id !== Auth::id()) {
            $ranking->user->notify(new RankingInteraction(Auth::user(), $ranking, 'vote'));
        }

        return redirect()->back()->with('success', __('Has votat correctament!'));
    }

    public function unvote(Ranking $ranking)
    {
        RankingVote::where('user_id', Auth::id())
            ->whereHas('option', fn($q) => $q->where('ranking_id', $ranking->id))
            ->delete();

        return redirect()->back()->with('success', __('Has eliminat el teu vot!'));
    }
}