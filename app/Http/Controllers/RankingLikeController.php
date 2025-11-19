<?php

namespace App\Http\Controllers;

use App\Models\Ranking;
use Illuminate\Http\Request;

class RankingLikeController extends Controller
{
    public function store(Request $request, Ranking $ranking)
    {
        $ranking->likes()->firstOrCreate([
            'user_id' => $request->user()->id,
        ]);

        $ranking->update(['likes_count' => $ranking->likes()->count()]);

        return back();
    }

    public function destroy(Request $request, Ranking $ranking)
    {
        $ranking->likes()->where('user_id', $request->user()->id)->delete();

        $ranking->update(['likes_count' => $ranking->likes()->count()]);
        
        return back();
    }
}