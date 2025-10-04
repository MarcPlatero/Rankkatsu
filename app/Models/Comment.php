<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'ranking_id',
        'user_id',
        'content',
    ];

    public function ranking()
    {
        return $this->belongsTo(Ranking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function votes()
    {
        return $this->hasMany(CommentVote::class);
    }

    public function likesCount()
    {
        return $this->votes()->where('is_like', true)->count();
    }

    public function dislikesCount()
    {
        return $this->votes()->where('is_like', false)->count();
    }
}
