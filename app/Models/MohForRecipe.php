<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MohForRecipe extends Model
{
    protected $table = 'moh_for_recipe';

    protected $fillable = [
        'product_id',
        'name',
        'oh_type',
        'price',      // Make sure price is included in fillable
        'percentage', // Make sure percentage is included in fillable
    ];
}