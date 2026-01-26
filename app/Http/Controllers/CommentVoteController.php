<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\CommentVote;
use Illuminate\Support\Facades\Auth;

class CommentVoteController extends Controller
{
    public function store(Request $request, Comment $comment)
    {
        $request->validate([
            'is_like' => 'required|boolean',
        ]);

        // Update o create (si ja havia votat, actualitza)
        $vote = CommentVote::updateOrCreate(
            [
                'comment_id' => $comment->id,
                'user_id' => Auth::id(),
            ],
            [
                'is_like' => (bool) $request->input('is_like'),
            ]
        );

        return redirect()->back()->with('success', __('Vot registrat!'));
    }
}