<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ranking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // Desa un comentari en un rànquing (només usuari autenticat)
    public function store(Request $request, Ranking $ranking)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ], [
            'content.required' => 'El comentari no pot estar buit.',
            'content.max' => 'El comentari no pot superar els 1000 caràcters.',
        ]);

        $comment = $ranking->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->input('content'),
        ]);

        return redirect()->back()->with('success', 'Comentari publicat!');
    }

    // Esborra un comentari: només l'autor o el creador del ranking poden eliminar-lo
    public function destroy(Ranking $ranking, Comment $comment)
    {
        $userId = Auth::id();

        if (! $userId) {
            abort(403);
        }

        // Permís: autor del comentari o creador del ranking
        if ($comment->user_id !== $userId && $ranking->user_id !== $userId) {
            abort(403);
        }

        $comment->delete();

        return redirect()->back()->with('success', 'Comentari eliminat correctament.');
    }
}
