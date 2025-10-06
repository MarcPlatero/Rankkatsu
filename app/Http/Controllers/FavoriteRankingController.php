<?php

namespace App\Http\Controllers;

use App\Models\Ranking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteRankingController extends Controller
{
    public function toggle(Request $request, Ranking $ranking)
    {
        $user = Auth::user();

        if ($user->favoriteRankings()->where('ranking_id', $ranking->id)->exists()) {
            // Si ja és favorit → eliminar
            $user->favoriteRankings()->detach($ranking->id);
            return response()->json(['favorited' => false]);
        } else {
            // Si no és favorit → afegir
            $user->favoriteRankings()->attach($ranking->id);
            return response()->json(['favorited' => true]);
        }
    }

    public function check(Ranking $ranking)
    {
        $user = Auth::user();

        $isFavorited = $user->favoriteRankings()->where('ranking_id', $ranking->id)->exists();

        return response()->json(['favorited' => $isFavorited]);
    }
}
