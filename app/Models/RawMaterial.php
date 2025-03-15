<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    use HasFactory;

    protected $table = 'raw_materials';

    protected $fillable = [
        'name',
        'rmcode',
        'uom',
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
        'price',
        'update_frequency',
        'price_update_frequency',
        'price_threshold',
        'hsncode',
        'itemweight',
        'itemType_id',
        'tax',
    ];

    // public function category1()
    // {
    //     return $this->belongsTo(Category::class, 'category_id1');
    // }

    // public function category2()
    // {
    //     return $this->belongsTo(Category::class, 'category_id2');
    // }

    // public function category3()
    // {
    //     return $this->belongsTo(Category::class, 'category_id3');
    // }

    // public function category4()
    // {
    //     return $this->belongsTo(Category::class, 'category_id4');
    // }

    // public function category5()
    // {
    //     return $this->belongsTo(Category::class, 'category_id5');
    // }
}
