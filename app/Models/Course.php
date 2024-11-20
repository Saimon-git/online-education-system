<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    /** @use HasFactory<\Database\Factories\CourseFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'age_group',
    ];

    public function category(): belongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function videos(): hasMany
    {
        return $this->hasMany(Video::class);
    }

    /**
     * Get the users associated with the course.
     *
     * This function defines a many-to-many relationship between the Course model
     * and the User model, allowing a course to be associated with multiple users
     * and vice versa.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany The relationship instance.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_courses')->withPivot('progress')->withTimestamps();
    }
}
