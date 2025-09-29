<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RankingVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'ranking_id',
        'ranking_option_id',
        'user_id',
    ];

    public function ranking()
    {
        return $this->belongsTo(Ranking::class);
    }

    public function option()
    {
        return $this->belongsTo(RankingOption::class, 'ranking_option_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
