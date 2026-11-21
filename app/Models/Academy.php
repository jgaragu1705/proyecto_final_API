<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Academy extends Model
{
    protected $fillable = [
        'name',
        'description',
        'address',
        'phone',
        'email',
    ];

    /**
     * Get the user that owns the academy.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the courses for the academy.
     */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    // Comprobar si un usuario es propietario de la academia
    public function isOwnedBy(User $user): bool
    {
        return (int) $this->user_id === (int) $user->id;
    }
}
