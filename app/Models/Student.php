<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    protected $fillable = [
        'name',
        'surname',
        'email',
        'phone',
        'date_of_birth',
    ];

    /**
     * Get the courses in which the student is enrolled.
     */
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class)
                    ->withPivot('enrolled_at')
                    ->withTimestamps();
    }
}
