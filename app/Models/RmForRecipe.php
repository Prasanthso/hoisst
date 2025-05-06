<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RmForRecipe extends Model
{
    protected $table = 'rm_for_recipe';

    protected $fillable = [
        'raw_material_id',
        'product_id',
        'quantity',
        'code',
        'uom',
        'price',
        'amount',
        'store_id'
    ];

}
