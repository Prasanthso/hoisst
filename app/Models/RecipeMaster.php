<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeMaster extends Model
{
    use HasFactory;
    protected $table = 'recipe_master';

    protected $fillable = [
        'product_id',
        'rpcode',
        'Output',
        'uom',
        'totalCost',
        'singleCost',
    ];

    // public function product()
    // {
    //     return $this->belongsTo(ProductMaster::class, 'product_id');
    // }
}
