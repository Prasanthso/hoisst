<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RawMaterial;

class RmPriceUpdateAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'store_id',
        'raw_material_ids',
        'alerted_at',
        'channel',
        'alert_type',
    ];

    protected $casts = [
        'raw_material_ids' => 'array',
        'alerted_at' => 'datetime',
    ];

    // 🔽 Add this accessor to return RawMaterial instances
    public function getRawMaterialsAttribute()
    {
        return RawMaterial::whereIn('id', $this->raw_material_ids ?? [])->get();
    }
}
