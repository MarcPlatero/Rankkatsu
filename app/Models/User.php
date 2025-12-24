<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Billable; 

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'profile_photo_path',
        'is_premium',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_premium' => 'boolean',
        ];
    }

    public function rankings()
    {
        return $this->hasMany(Ranking::class);
    }

    public function rankingVotes()
    {
        return $this->hasMany(RankingVote::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function favoriteRankings()
    {
         return $this->belongsToMany(Ranking::class, 'favorite_rankings')->withTimestamps();
    }

    public function rankingLikes()
    {
        return $this->hasMany(RankingLike::class);
    }

    public function getProfilePhotoUrlAttribute(): ?string
    {
        $path = $this->profile_photo_path;

        if (!$path) return null;
        if (str_starts_with($path, 'pixel-')) return null;
        if (str_starts_with($path, 'avatars/')) return asset('images/' . $path);

        return Storage::disk('public')->url($path);
    }

}