<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'headline',
        'bio',
        'website',
        'github_handle',
    ];

    /**
     * Each profile belongs to exactly one user (one-to-one inverse).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
