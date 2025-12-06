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
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, Billable; 

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'profile_photo_path',
        'is_premium',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     *
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
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

    public function getProfilePhotoUrlAttribute(): ?string
    {
        $path = $this->profile_photo_path;

        if (!$path) {
            return null;
        }

        if (str_starts_with($path, 'avatars/')) {
            return asset('images/' . $path);
        }

        return Storage::disk('public')->url($path);
    }

    public function rankingLikes()
    {
        return $this->hasMany(RankingLike::class);
    }
}