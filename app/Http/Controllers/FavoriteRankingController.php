<?php

namespace App\Http\Controllers;

use App\Models\Ranking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteRankingController extends Controller
{
    /**
     * Gestiona l'acció d'afegir/treure favorits
     */
    public function toggle(Request $request, Ranking $ranking)
    {
        $user = $request->user();

        // Si ja existeix, l'esborrem
        if ($user->favoriteRankings()->where('ranking_id', $ranking->id)->exists()) {
            $user->favoriteRankings()->detach($ranking->id);
            
            return back()->with('success', 'Eliminat dels favorits.');
        } 
        // Si no existeix, l'afegim
        else {
            $user->favoriteRankings()->attach($ranking->id);
            
            return back()->with('success', 'Afegit als favorits!');
        }
    }
}