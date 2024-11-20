<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    protected $fillable = ['name'];

    public function courses(): hasMany
    {
        return $this->hasMany(Course::class);
    }

    public function videos(): hasMany
    {
        return $this->hasMany(Video::class);
    }
}