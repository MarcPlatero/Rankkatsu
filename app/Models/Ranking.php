<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ranking extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'user_id',
    ];

    public function options()
    {
        return $this->hasMany(RankingOption::class);
    }

    public function votes()
    {
        return $this->hasMany(RankingVote::class);
    }

}
