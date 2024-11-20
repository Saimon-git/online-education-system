<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
        ];
    }

    /**
     * Establish a many-to-many relationship between the User and Course models.
     *
     * @return BelongsToMany The relationship instance between User and Course.
     */
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class,'user_courses')->withPivot('progress')->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likedVideos()
    {
        return $this->belongsToMany(Video::class, 'user_video_likes');
    }

    public function isAdmin()
    {
        return $this->hasRole('admin'); // Verifica si el usuario tiene el rol admin
    }

    public function isUser()
    {
        return $this->hasRole('user'); // Verifica si el usuario tiene el rol admin
    }

    public function completedVideos()
    {
        return $this->belongsToMany(Video::class, 'user_video_completions')->withTimestamps();
    }

}
