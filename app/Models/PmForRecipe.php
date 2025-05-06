<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PmForRecipe extends Model
{
    protected $table = 'pm_for_recipe';

    protected $fillable = [
        'packing_material_id',
        'product_id',
        'quantity',
        'code',
        'uom',
        'price',
        'amount',
        'store_id'
    ];
}
