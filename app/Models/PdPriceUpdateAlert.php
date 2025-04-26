<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PdPriceUpdateAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_ids',
        'alerted_at',
        'channel',
        'alert_type',
    ];

    protected $casts = [
        'product_ids' => 'array',
        'alerted_at' => 'datetime',
    ];
}
