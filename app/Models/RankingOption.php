<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RankingOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'ranking_id',
        'name',
        'image',
        'is_suspicious',
        'is_approved',
    ];

    public function ranking()
    {
        return $this->belongsTo(Ranking::class);
    }

    public function votes()
    {
        return $this->hasMany(RankingVote::class);
    }
}
