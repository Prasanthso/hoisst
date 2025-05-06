<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackingMaterial extends Model
{
    use HasFactory;

    protected $table = 'packing_materials';

    protected $fillable = [
        'name',
        'pmcode',
        'hsnCode',
        'uom',
        'itemWeight',
        'category_id1',
        'category_id2',
        'category_id3',
        'category_id4',
        'category_id5',
        'category_id6',
        'category_id7',
        'category_id8',
        'category_id9',
        'category_id10',
        'itemType_id',
        'price',
        'tax',
        'update_frequency',
        'price_update_frequency',
        'price_threshold',
        'store_id'
    ];
}
