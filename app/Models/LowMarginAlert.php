<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LowMarginAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'calculated_margin',
        'threshold_margin',
        'alerted_at',
        'channel',
    ];
}
