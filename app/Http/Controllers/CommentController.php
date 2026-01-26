<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentVote;
use App\Models\Ranking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\RankingInteraction;

class CommentController extends Controller
{
    // Desa un comentari en un rànquing (només usuari autenticat)
    public function store(Request $request, Ranking $ranking)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ], [
            'content.required' => __('El comentari no pot estar buit.'),
            'content.max' => __('El comentari no pot superar els 1000 caràcters.'),
        ]);

        // Llista de paraules prohibides des de config/banned.php
        $bannedWords = config('banned.words', []);

        // Comprovar si el comentari conté alguna paraula prohibida
        foreach ($bannedWords as $badWord) {
            if (preg_match("/\b" . preg_quote($badWord, '/') . "\b/i", $validated['content'])) {
                return redirect()->back()
                    ->with('error', __('No utilitzis paraules malsonants al comentari.'))
                    ->withInput();
            }
        }

        // Guardar comentari si passa el filtre
        $ranking->comments()->create([
            'user_id' => Auth::id(),
            'content' => $validated['content'],
        ]);

        // Notificació
        if ($ranking->user_id !== Auth::id()) {
            $ranking->user->notify(new RankingInteraction(Auth::user(), $ranking, 'comment'));
        }

        return redirect()->back()->with('success', __('Comentari publicat!'));
    }

    public function unvote(Comment $comment)
    {
        if (!Auth::check()) {
            return back()->with('error', __('Has d\'iniciar sessió per treure el vot.'));
        }

        CommentVote::where('user_id', Auth::id())
            ->where('comment_id', $comment->id)
            ->delete();

        return back();
    }

    // Esborra un comentari
    public function destroy(Ranking $ranking, Comment $comment)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        if ($comment->ranking_id !== $ranking->id) {
            $comment = $ranking->comments()->find($comment->id);

            if (!$comment) {
                abort(403, __('El comentari no pertany a aquest rànquing.'));
            }
        }

        if (
            $comment->user_id !== $user->id &&
            $ranking->user_id !== $user->id &&
            !$user->is_admin
        ) {
            abort(403);
        }

        $comment->delete();

        return redirect()->back()->with('success', __('Comentari eliminat correctament.'));
    }
}