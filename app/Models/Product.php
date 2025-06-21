<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product_master';

    protected $fillable = [
        'name',
        'pdcode',
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
        'purcCost',
        'margin',
        'price', // for this MRP
        'tax',
        'update_frequency',
        // 'cost',
        'recipe_crated_status',
        'price_update_frequency',
        'price_threshold',
        'minimum_stock_unit',
        'minimum_stock_qty',
        'store_id',
        'status',
    ];

    // public function itemtypeId()
    // {
    //     return $this->belongsTo(ItemType::class, 'itemType_id', 'id');
    // }
}
