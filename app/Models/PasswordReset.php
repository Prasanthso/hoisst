<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PasswordReset extends Model
{
    public $timestamps = false; // because we only have 'created_at'

    protected $table = 'password_reset_tokens';

    protected $fillable = [
        'email',
        'token',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Check if the token is expired (optional helper).
     */
    public function isExpired(): bool
    {
        return $this->created_at->addMinutes(60)->isPast(); // valid for 60 minutes
    }
}
