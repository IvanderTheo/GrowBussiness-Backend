<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasUuids, HasApiTokens;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];
    protected $hidden = [
        'password',
        'remember_token'
    ];
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    protected function chatbot(): HasMany {
        return $this->hasMany(AIChatSessions::class,'user_id');
    }
    protected function schedule(): HasMany {
        return $this->hasMany(Schedules::class,'user_id','id');
    }

    protected function forum(): HasMany {
        return $this->hasMany(Forum::class,'user_ud','id');
    }
    protected function comment(): HasMany {
        return $this->hasMany(ForumComments::class,'user_id','id');
    }
    protected function product() : HasMany {
        return $this->hasMany(Products::class,'user_id','id');
    }
}
