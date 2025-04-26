<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PmPriceUpdateAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'packing_material_ids',
        'alerted_at',
        'channel',
        'alert_type',
    ];

    protected $casts = [
        'packing_material_ids' => 'array',
        'alerted_at' => 'datetime',
    ];
}
