<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use phpDocumentor\Reflection\Types\Boolean;

class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory;

    protected $fillable = [
        'content',
        'is_approved',
        'user_id',
        'video_id',
        'status',
    ];

    /**
     * Get the user that owns the comment.
     *
     * @return BelongsTo The relationship instance between Comment and User.
     */
    public function user(): belongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the video that the comment is related to.
     *
     * @return BelongsTo The relationship instance between Comment and Video.
     */
    public function video(): belongsTo
    {
        return $this->belongsTo(Video::class);
    }

    /*public function getIsApprovedAttribute($value): boolean
    {
        return (bool) $value;
    }*/

}
