<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Video extends Model
{
    /** @use HasFactory<\Database\Factories\VideoFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'url',
        'duration',
        'likes',
        'views',
        'category_id',
        'course_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    /**
     * Get the course that owns the video.
     *
     * This function defines the inverse of the one-to-many relationship
     * between Course and Video models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *         Returns a BelongsTo instance representing the relationship
     *         to the Course model.
     */
    public function course(): belongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'user_video_likes');
    }

    public function completedByUsers()
    {
        return $this->belongsToMany(
            User::class,
            'user_video_completions',
            'video_id',
            'user_id'
        )->withPivot('course_id', 'created_at', 'updated_at');
    }

    public function convertToEmbedUrl(string $url): string
    {
        if (str_contains($url, 'youtube.com/watch')) {
            $videoId = explode('v=', $url)[1];
            $videoId = explode('&', $videoId)[0];
            return "https://www.youtube.com/embed/$videoId";
        }
        return $url; // Si no es una URL estándar de YouTube, retorna la original.
    }

}
