<?php

namespace App\Http\Controllers;

use App\Models\Ranking;
use Illuminate\Http\Request;
use App\Notifications\RankingInteraction;

class RankingLikeController extends Controller
{
    public function store(Request $request, Ranking $ranking)
    {
        $like = $ranking->likes()->firstOrCreate(['user_id' => $request->user()->id]);

        if ($like->wasRecentlyCreated && $ranking->user_id !== $request->user()->id) {
            $ranking->user->notify(new RankingInteraction($request->user(), $ranking, 'like'));
        }

        $ranking->update(['likes_count' => $ranking->likes()->count()]);

        return back()->with('success', 'T\'agrada aquest rànquing!');
    }

    public function destroy(Request $request, Ranking $ranking)
    {
        $ranking->likes()->where('user_id', $request->user()->id)->delete();

        $ranking->update(['likes_count' => $ranking->likes()->count()]);
        
       return back()->with('success', 'Ja no t\'agrada aquest rànquing.');
    }
}