<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'pdcode',
        'uom',
        'category_id1',
        'category_id2',
        'category_id3',
        'category_id4',
        'category_id5',
        'price',
        'price_update_frequency',
        'price_threshold'
    ];
}
