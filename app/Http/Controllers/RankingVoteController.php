<?php

namespace App\Http\Controllers;

use App\Models\Ranking;
use App\Models\RankingOption;
use App\Models\RankingVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RankingVoteController extends Controller
{
    public function vote(Request $request, Ranking $ranking)
    {
        $request->validate([
            'option_id' => 'required|exists:ranking_options,id',
        ]);

        $option = RankingOption::findOrFail($request->option_id);

        // Comprovar si ja ha votat
        $existingVote = RankingVote::where('ranking_id', $ranking->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingVote) {
            return back()->with('error', 'Ja has votat en aquest rànquing');
        }

        RankingVote::create([
            'ranking_id' => $ranking->id,
            'ranking_option_id' => $option->id,
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Vot registrat correctament!');
    }

    public function unvote(Ranking $ranking)
    {
        RankingVote::where('ranking_id', $ranking->id)
            ->where('user_id', Auth::id())
            ->delete();

        return back()->with('success', 'Has eliminat el teu vot.');
    }
}
