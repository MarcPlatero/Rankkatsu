<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RankingLike extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'ranking_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ranking()
    {
        return $this->belongsTo(Ranking::class);
    }
}