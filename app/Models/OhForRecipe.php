<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OhForRecipe extends Model
{
    protected $table = 'oh_for_recipe';

    protected $fillable = [
        'overheads_id',
        'product_id',
        'quantity',
        'code',
        'uom',
        'price',
        'amount',
        'percentage',
        'store_id'
    ];
}
